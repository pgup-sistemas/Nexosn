/**
 * NEXOSN — Service Worker
 * Estratégias: Cache First | Stale-While-Revalidate | Network First | Network Only
 */

const SW_VERSION = 'nexosn-v1';

const PRECACHE = [
    '/offline.html',
    '/manifest.json',
    '/images/icon-192.png',
    '/images/icon-512.png',
];

// ── Padrões por estratégia ──────────────────────────────────────────────────

// Cache First: assets com hash (Vite) e fontes locais — nunca mudam
const CACHE_FIRST_PATTERNS = [
    /\/build\/assets\//,
    /\/fonts\/inter-/,
    /\/images\/icon-/,
    /\/images\/og-default/,
];

// Stale-While-Revalidate: perfil público e imagens do Storage
const SWR_PATTERNS = [
    /^https?:\/\/[^/]+\/u\/[^/]+$/,   // /u/{slug}
    /\/storage\//,                      // fotos do perfil, capa, galeria
];

// Network First (com fallback cache): slots de agenda
const NETWORK_FIRST_PATTERNS = [
    /\/u\/[^/]+\/agendar\/slots/,
];

// Network Only: painel, webhook, confirmações — nunca cachear
const NETWORK_ONLY_PATTERNS = [
    /\/dashboard/,
    /\/webhook/,
    /\/appointments\/[^/]+\/(confirm|refuse)/,
    /\/livewire/,
    /\/api\//,
];

// ── Install: pré-cachear recursos estáticos críticos ───────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(SW_VERSION).then((cache) => cache.addAll(PRECACHE))
    );
    self.skipWaiting();
});

// ── Activate: limpar caches antigos ───────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.filter((k) => k !== SW_VERSION).map((k) => caches.delete(k))
            )
        )
    );
    self.clients.claim();
});

// ── Fetch: interceptar requisições ────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = request.url;

    // Ignorar requisições não-GET e cross-origin sem padrão definido
    if (request.method !== 'GET') return;

    // Network Only — nunca interceptar
    if (NETWORK_ONLY_PATTERNS.some((p) => p.test(url))) return;

    // Cache First
    if (CACHE_FIRST_PATTERNS.some((p) => p.test(url))) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // Stale-While-Revalidate
    if (SWR_PATTERNS.some((p) => p.test(url))) {
        event.respondWith(staleWhileRevalidate(request));
        return;
    }

    // Network First
    if (NETWORK_FIRST_PATTERNS.some((p) => p.test(url))) {
        event.respondWith(networkFirst(request, 3000));
        return;
    }
});

// ── Estratégias ────────────────────────────────────────────────────────────

async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(SW_VERSION);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return caches.match('/offline.html');
    }
}

async function staleWhileRevalidate(request) {
    const cache = await caches.open(SW_VERSION);
    const cached = await cache.match(request);

    const fetchPromise = fetch(request)
        .then((response) => {
            if (response.ok) cache.put(request, response.clone());
            return response;
        })
        .catch(() => cached || caches.match('/offline.html'));

    return cached || fetchPromise;
}

async function networkFirst(request, timeoutMs = 3000) {
    const cache = await caches.open(SW_VERSION);
    try {
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), timeoutMs);
        const response = await fetch(request, { signal: controller.signal });
        clearTimeout(timer);
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch {
        const cached = await cache.match(request);
        return cached || caches.match('/offline.html');
    }
}
