@extends('layouts.card')

@section('title', $card->display_name)
@section('description', $card->bio
    ? \Illuminate\Support\Str::limit(strip_tags($card->bio), 155)
    : ($card->title ? $card->display_name . ' — ' . $card->title . ($card->company ? ' · ' . $card->company : '') . ' | Identidade digital na NEXOSN.' : 'Identidade digital de ' . $card->display_name . ' na plataforma NEXOSN.')
)

@section('og_image', $card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png'))

@section('card_colors')
<style>
    :root {
        --card-primary: {{ $card->primary_color }};
        --card-button:  {{ $card->button_color }};
        --ui-icon: #b0b7c3;
        --ui-label: #9ca3af;
        --ui-text: #374151;
        --ui-heading: #111827;
        --ui-border: rgba(0,0,0,0.07);
        --ui-bg: #f7f7f6;
        --ui-section-bg: #ffffff;
        --ui-tap: rgba(0,0,0,0.03);
    }

    [x-cloak] { display: none !important; }

    /* ── Wrapper de seções ── */
    .sections-wrap {
        padding: 2px 14px 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* ── Card de seção ── */
    .cs {
        background: var(--ui-section-bg);
        border-radius: 16px;
        border: 1px solid var(--ui-border);
        overflow: hidden;
    }

    /* ── Header da seção ── */
    .cs-head {
        width: 100%; background: none; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between;
        padding: 13px 16px; gap: 10px; text-align: left;
        -webkit-tap-highlight-color: transparent;
    }
    .cs-head:active { background: var(--ui-tap); }

    .cs-label {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 600; color: var(--ui-label);
        text-transform: uppercase; letter-spacing: .6px;
    }

    .cs-chevron {
        width: 15px; height: 15px; flex-shrink: 0;
        color: #d1d5db; transition: transform .22s ease;
    }

    /* ── Corpo da seção ── */
    .cs-body { padding: 0 16px 14px; }
    .cs-body-flush { padding: 0; }

    /* ── Linhas de contato ── */
    .crow {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--ui-border);
        text-decoration: none; color: var(--ui-text);
        font-size: 14px; line-height: 1.4;
        transition: color .12s;
    }
    .crow:last-child { border-bottom: none; padding-bottom: 0; }
    .crow:active { color: var(--ui-heading); }

    /* ── Botões de ação ── */
    .abtn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 13px 16px; border-radius: 12px;
        font-size: 14px; font-weight: 600; cursor: pointer;
        text-decoration: none; border: none;
        transition: opacity .15s, transform .12s;
        -webkit-tap-highlight-color: transparent;
    }
    .abtn:active { transform: scale(.98); opacity: .85; }
    .abtn-primary { background-color: var(--card-button); color: #fff; }
    .abtn-ghost {
        background: var(--ui-section-bg);
        color: var(--ui-text);
        border: 1px solid var(--ui-border);
    }
    .abtn-pix {
        background: linear-gradient(100deg, #F77F00 0%, #FCBF49 100%);
        color: #431900; font-weight: 700; letter-spacing: .1px;
    }

    /* ── Social pills ── */
    .spill {
        display: inline-flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 12px;
        transition: opacity .12s, transform .1s;
        background-color: var(--card-primary);
        -webkit-tap-highlight-color: transparent;
    }
    .spill:active { transform: scale(.88); opacity: .8; }
</style>
@endsection

@section('content')

{{-- ═══════════════════ HEADER / CAPA ═══════════════════ --}}
<header style="position: relative;">
    <div style="height: 160px; width: 100%; overflow: hidden; position: relative; background-color: var(--card-primary);">
        @if ($card->cover_photo)
            <img src="{{ Storage::url($card->cover_photo) }}"
                 alt="Capa de {{ $card->display_name }}"
                 style="width:100%;height:100%;object-fit:cover;object-position:center center;display:block;">
        @endif
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.04) 0%,rgba(0,0,0,0.18) 100%);"></div>
    </div>

    {{-- Avatar --}}
    <div style="position:absolute;left:50%;transform:translateX(-50%);top:118px;">
        <div style="width:80px;height:80px;border-radius:9999px;overflow:hidden;
                    border:3px solid #fff;box-shadow:0 2px 12px rgba(0,0,0,0.14);
                    background-color:var(--card-primary);">
            @if ($card->profile_photo)
                <img src="{{ Storage::url($card->profile_photo) }}"
                     alt="{{ $card->display_name }}"
                     style="width:100%;height:100%;object-fit:cover;object-position:top center;display:block;">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;
                            justify-content:center;color:#fff;font-size:28px;font-weight:800;letter-spacing:-1px;">
                    {{ strtoupper(substr($card->display_name, 0, 1)) }}
                </div>
            @endif
        </div>
    </div>
</header>

{{-- ═══════════════════ IDENTIDADE ═══════════════════ --}}
<div style="padding:52px 20px 18px;text-align:center;">

    @if ($card->logo)
        <img src="{{ Storage::url($card->logo) }}"
             alt="Logo {{ $card->company }}"
             style="height:28px;margin:0 auto 10px;object-fit:contain;display:block;">
    @endif

    <h1 style="font-size:20px;font-weight:700;color:#111827;line-height:1.25;letter-spacing:-.3px;margin:0 0 4px;">
        {{ $card->display_name }}
    </h1>

    @if ($card->title || $card->company)
        <p style="font-size:13px;color:#9ca3af;margin:0;font-weight:400;">
            {{ implode(' · ', array_filter([$card->title, $card->company])) }}
        </p>
    @endif

</div>

{{-- ═══════════════════ LINKS ═══════════════════ --}}
@php
    $socialTypes = ['instagram.com','wa.me','whatsapp','linkedin.com','tiktok.com','youtube.com','twitter.com','x.com','facebook.com','t.me','telegram','pinterest.com','spotify.com'];
    $socialLinks = $card->links->filter(fn ($l) => collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
    $customLinks = $card->links->filter(fn ($l) => !collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
@endphp

{{-- Redes sociais — ícones simples, sem excesso --}}
@if ($socialLinks->isNotEmpty())
<div style="display:flex;justify-content:center;gap:10px;padding:0 16px 16px;flex-wrap:wrap;">
    @foreach ($socialLinks as $link)
        <a href="{{ $link->url }}" target="_blank" rel="noopener"
           class="spill" title="{{ $link->label }}">
            <i data-lucide="{{ $link->lucide_icon }}" style="width:17px;height:17px;color:rgba(255,255,255,0.85);"></i>
        </a>
    @endforeach
</div>
@endif

{{-- Links customizados (com tracking de cliques) --}}
@if ($customLinks->isNotEmpty())
<div style="padding:0 14px 8px;display:flex;flex-direction:column;gap:7px;">
    @foreach ($customLinks as $link)
        <a href="{{ route('card.link.click', [$card->slug, $link->id]) }}" rel="noopener" class="abtn abtn-primary">
            <i data-lucide="{{ $link->lucide_icon }}" style="width:16px;height:16px;opacity:.85;"></i>
            <span>{{ $link->label }}</span>
        </a>
    @endforeach
</div>
@endif

{{-- ════════════ SEÇÕES ════════════ --}}
<div class="sections-wrap">

{{-- ── SOBRE ── --}}
@if ($card->bio)
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="align-left" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Sobre
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <p style="font-size:14px;color:#4b5563;line-height:1.65;margin:0;white-space:pre-line;">{{ $card->bio }}</p>
    </div>
</div>
@endif

{{-- ── CONTATO ── --}}
@if ($card->contact_phone || $card->contact_email || $card->website)
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="phone" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Contato
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        @if ($card->contact_phone)
        <a href="tel:{{ preg_replace('/\D/', '', $card->contact_phone) }}" class="crow">
            <i data-lucide="phone" style="width:15px;height:15px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span>{{ $card->contact_phone }}</span>
        </a>
        @endif

        @if ($card->contact_email)
        <a href="mailto:{{ $card->contact_email }}" class="crow">
            <i data-lucide="mail" style="width:15px;height:15px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span>{{ $card->contact_email }}</span>
        </a>
        @endif

        @if ($card->website)
        <a href="{{ $card->website }}" target="_blank" rel="noopener" class="crow">
            <i data-lucide="globe" style="width:15px;height:15px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span>{{ parse_url($card->website, PHP_URL_HOST) ?? $card->website }}</span>
        </a>
        @endif

    </div>
</div>
@endif

{{-- ── LOCALIZAÇÃO ── --}}
@if ($card->address)
@php $mapsUrl = 'https://maps.google.com/?q=' . urlencode($card->address); @endphp
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="map-pin" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Localização
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Linha de endereço clicável --}}
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
           style="display:flex;align-items:flex-start;gap:10px;padding:10px 12px;border-radius:10px;
                  background:#f7f7f6;text-decoration:none;margin-bottom:8px;">
            <i data-lucide="map-pin" style="width:15px;height:15px;color:var(--ui-icon);margin-top:1px;flex-shrink:0;"></i>
            <span style="font-size:13px;color:#374151;line-height:1.45;flex:1;">{{ $card->address }}</span>
            <i data-lucide="arrow-up-right" style="width:13px;height:13px;color:#d1d5db;flex-shrink:0;margin-top:2px;"></i>
        </a>

        {{-- Ações --}}
        <div style="display:flex;gap:7px;">
            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
               style="flex:1;display:flex;align-items:center;justify-content:center;gap:5px;
                      padding:9px 12px;border-radius:9px;font-size:12px;font-weight:600;
                      border:1px solid var(--ui-border);background:#fff;color:#4b5563;text-decoration:none;">
                <i data-lucide="map" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                Google Maps
            </a>
            <button type="button" onclick="compartilharLocalizacao()"
                    style="flex:1;display:flex;align-items:center;justify-content:center;gap:5px;
                           padding:9px 12px;border-radius:9px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#4b5563;cursor:pointer;">
                <i data-lucide="share-2" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                Compartilhar
            </button>
        </div>
        <div id="loc-copiado" style="display:none;margin-top:8px;text-align:center;font-size:12px;color:#16a34a;font-weight:600;">
            ✓ Link copiado!
        </div>
    </div>
</div>

<script>
function compartilharLocalizacao() {
    const url = '{{ $mapsUrl }}';
    if (navigator.share) {
        navigator.share({ title: '{{ addslashes($card->display_name) }} — Localização', text: '{{ addslashes($card->address) }}', url }).catch(()=>{});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const el = document.getElementById('loc-copiado');
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        });
    }
}
</script>
@endif

{{-- ── PIX ── --}}
@if ($card->pix_key)
<button type="button" onclick="copiarPix()" class="abtn abtn-pix" style="border-radius:16px;">
    <i data-lucide="qr-code" style="width:17px;height:17px;opacity:.75;"></i>
    Pagar via PIX
</button>
<div id="pix-copiado" style="display:none;text-align:center;font-size:12px;color:#16a34a;font-weight:600;margin-top:-2px;margin-bottom:2px;">
    ✓ Chave PIX copiada!
</div>
<script>
function copiarPix() {
    navigator.clipboard.writeText('{{ $card->pix_key }}').then(() => {
        const el = document.getElementById('pix-copiado');
        el.style.display = 'block';
        setTimeout(() => el.style.display = 'none', 3000);
    });
}
</script>
@endif

{{-- ── GALERIA com Lightbox/Slideshow ── --}}
@if ($card->photos->isNotEmpty())
@php $photos = $card->photos; @endphp
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="image" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Galeria
            <span style="font-size:10px;color:var(--ui-label);font-weight:500;">({{ $photos->count() }})</span>
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body-flush" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:3px;padding:0 0 12px;">
            @foreach ($photos as $i => $photo)
            <button type="button" onclick="nexosnGallery.open({{ $i }})"
               style="aspect-ratio:1;overflow:hidden;background:#f0f0ee;display:block;
                      border:none;padding:0;cursor:zoom-in;position:relative;">
                <img src="{{ $photo->thumbnail_url ?? Storage::url($photo->path) }}"
                     alt="{{ $photo->caption ?? 'Foto ' . ($i+1) }}"
                     loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .2s;"
                     onmouseenter="this.style.transform='scale(1.04)'" onmouseleave="this.style.transform=''">
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="nexosn-lightbox"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.94);
            flex-direction:column;align-items:center;justify-content:center;"
     onclick="if(event.target===this)nexosnGallery.close()">

    {{-- Toolbar --}}
    <div style="position:absolute;top:0;left:0;right:0;display:flex;align-items:center;justify-content:space-between;
                padding:14px 16px;background:linear-gradient(to bottom,rgba(0,0,0,.6),transparent);z-index:2;">
        <span id="lb-counter" style="font-size:12px;color:rgba(255,255,255,.6);font-weight:500;"></span>
        <button onclick="nexosnGallery.close()"
                style="background:rgba(255,255,255,.12);border:none;color:#fff;width:34px;height:34px;
                       border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:18px;">×</button>
    </div>

    {{-- Imagem --}}
    <div style="width:100%;max-height:80vh;display:flex;align-items:center;justify-content:center;padding:60px 56px;">
        <img id="lb-img" src="" alt="" style="max-width:100%;max-height:76vh;object-fit:contain;border-radius:8px;
             transition:opacity .18s;box-shadow:0 8px 48px rgba(0,0,0,.5);">
    </div>

    {{-- Caption --}}
    <div id="lb-caption" style="font-size:13px;color:rgba(255,255,255,.7);text-align:center;
                                 padding:0 24px 16px;min-height:20px;"></div>

    {{-- Prev --}}
    <button onclick="nexosnGallery.prev()"
            style="position:absolute;left:8px;top:50%;transform:translateY(-50%);
                   background:rgba(255,255,255,.12);border:none;color:#fff;
                   width:40px;height:40px;border-radius:50%;display:flex;align-items:center;
                   justify-content:center;cursor:pointer;font-size:20px;backdrop-filter:blur(4px);">‹</button>

    {{-- Next --}}
    <button onclick="nexosnGallery.next()"
            style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                   background:rgba(255,255,255,.12);border:none;color:#fff;
                   width:40px;height:40px;border-radius:50%;display:flex;align-items:center;
                   justify-content:center;cursor:pointer;font-size:20px;backdrop-filter:blur(4px);">›</button>

    {{-- Dots --}}
    <div id="lb-dots" style="display:flex;gap:6px;padding-bottom:20px;"></div>
</div>

<script>
(function () {
    @php
    $photosJson = $photos->map(fn($p) => [
        'src'     => Storage::url($p->path),
        'thumb'   => $p->thumbnail_url ?? Storage::url($p->path),
        'caption' => $p->caption ?? '',
    ]);
    @endphp
    const photos = @json($photosJson);

    const lb      = document.getElementById('nexosn-lightbox');
    const lbImg   = document.getElementById('lb-img');
    const lbCap   = document.getElementById('lb-caption');
    const lbCount = document.getElementById('lb-counter');
    const lbDots  = document.getElementById('lb-dots');
    let current   = 0;

    function buildDots() {
        lbDots.innerHTML = '';
        photos.forEach((_, i) => {
            const d = document.createElement('div');
            d.style.cssText = 'width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.35);cursor:pointer;transition:background .15s';
            d.onclick = () => nexosnGallery.go(i);
            lbDots.appendChild(d);
        });
    }

    function updateDots() {
        [...lbDots.children].forEach((d, i) => {
            d.style.background = i === current ? '#fff' : 'rgba(255,255,255,.35)';
            d.style.width = i === current ? '18px' : '6px';
            d.style.borderRadius = '99px';
        });
    }

    window.nexosnGallery = {
        open(idx) {
            current = idx;
            lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            buildDots();
            this.go(idx);
        },
        close() {
            lb.style.display = 'none';
            document.body.style.overflow = '';
        },
        go(idx) {
            if (idx < 0) idx = photos.length - 1;
            if (idx >= photos.length) idx = 0;
            current = idx;
            lbImg.style.opacity = '0';
            setTimeout(() => {
                lbImg.src     = photos[idx].src;
                lbImg.alt     = photos[idx].caption || 'Foto ' + (idx + 1);
                lbCap.textContent = photos[idx].caption;
                lbCount.textContent = (idx + 1) + ' / ' + photos.length;
                lbImg.style.opacity = '1';
                updateDots();
            }, 90);
        },
        prev() { this.go(current - 1); },
        next() { this.go(current + 1); },
    };

    // Teclado
    document.addEventListener('keydown', e => {
        if (lb.style.display === 'none') return;
        if (e.key === 'ArrowRight') nexosnGallery.next();
        if (e.key === 'ArrowLeft')  nexosnGallery.prev();
        if (e.key === 'Escape')     nexosnGallery.close();
    });

    // Swipe touch
    let touchX = null;
    lb.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend',   e => {
        if (touchX === null) return;
        const diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) diff > 0 ? nexosnGallery.next() : nexosnGallery.prev();
        touchX = null;
    }, { passive: true });
})();
</script>
@endif

{{-- ── SALVAR CONTATO (client-side — funciona offline) ── --}}
<script id="vcard-data" type="application/json">
{
    "fn":    "{{ addslashes($card->display_name) }}",
    "title": "{{ addslashes($card->title ?? '') }}",
    "org":   "{{ addslashes($card->company ?? '') }}",
    "tel":   "{{ preg_replace('/\D/', '', $card->contact_phone ?? '') }}",
    "email": "{{ addslashes($card->contact_email ?? '') }}",
    "url":   "{{ url('/u/' . $card->slug) }}",
    "note":  "{{ addslashes(strip_tags($card->bio ?? '')) }}",
    "slug":  "{{ $card->slug }}"
}
</script>
<button type="button" onclick="nexosnDownloadVCard()" class="abtn abtn-ghost" style="border-radius:16px;">
    <i data-lucide="user-plus" style="width:16px;height:16px;color:var(--ui-icon);"></i>
    Salvar contato
</button>

{{-- ── COMPARTILHAR / QR CODE (seção colapsável, offline-safe) ── --}}
<div class="cs" x-data="{ open: false }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="share-2" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Compartilhar perfil
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- QR Code SVG inline — sem requisição ao servidor --}}
        <div id="qr-inline" style="display:flex;justify-content:center;padding:10px 0 14px;">
            {!! $qrSvg !!}
        </div>

        {{-- Linha do link --}}
        <div style="display:flex;align-items:center;gap:8px;background:var(--ui-bg);
                    border:1px solid var(--ui-border);border-radius:10px;padding:10px 12px;margin-bottom:10px;">
            <i data-lucide="link" style="width:13px;height:13px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span style="font-size:12px;color:#6b7280;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                nexosn.pageup.net.br/u/<strong style="color:#374151;">{{ $card->slug }}</strong>
            </span>
        </div>

        {{-- Botões de ação --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <button type="button" onclick="nexosnCopyLink(this)"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                           padding:11px 12px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#374151;cursor:pointer;">
                <i data-lucide="copy" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                <span>Copiar link</span>
            </button>
            <button type="button" onclick="nexosnShareNative()"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                           padding:11px 12px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#374151;cursor:pointer;">
                <i data-lucide="share-2" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                <span>Compartilhar</span>
            </button>
            <button type="button" onclick="nexosnDownloadQr()"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                           padding:11px 12px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#374151;cursor:pointer;
                           grid-column:1/-1;">
                <i data-lucide="download" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                <span>Baixar QR Code (PNG)</span>
            </button>
        </div>

        {{-- Feedback de cópia --}}
        <div id="share-feedback" style="display:none;text-align:center;font-size:12px;
                                        color:#16a34a;font-weight:600;margin-top:8px;"></div>
    </div>
</div>

<script>
const _profileUrl  = '{{ url('/u/' . $card->slug) }}';
const _profileName = '{{ addslashes($card->display_name) }}';
const _profileSlug = '{{ $card->slug }}';

// ── Copiar link offline-safe ──
function nexosnCopyLink(btn) {
    navigator.clipboard.writeText(_profileUrl).then(() => {
        const fb = document.getElementById('share-feedback');
        fb.textContent = '✓ Link copiado!';
        fb.style.display = 'block';
        setTimeout(() => fb.style.display = 'none', 3000);
    });
}

// ── Compartilhamento nativo (Web Share API) ──
function nexosnShareNative() {
    if (navigator.share) {
        navigator.share({ title: _profileName, url: _profileUrl }).catch(() => nexosnCopyLink());
    } else {
        nexosnCopyLink(null);
    }
}

// ── Download QR PNG via Canvas (sem servidor) ──
function nexosnDownloadQr() {
    const svgEl = document.querySelector('#qr-inline svg');
    if (!svgEl) return;
    const svgData = new XMLSerializer().serializeToString(svgEl);
    const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
    const url     = URL.createObjectURL(svgBlob);
    const img     = new Image();
    img.onload = () => {
        const canvas = document.createElement('canvas');
        canvas.width = canvas.height = 600;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, 600, 600);
        ctx.drawImage(img, 0, 0, 600, 600);
        URL.revokeObjectURL(url);
        const a = document.createElement('a');
        a.href = canvas.toDataURL('image/png');
        a.download = 'qr-' + _profileSlug + '.png';
        a.click();
    };
    img.src = url;
}

// ── Download vCard client-side (VCF 3.0, sem servidor) ──
function nexosnDownloadVCard() {
    const raw = document.getElementById('vcard-data');
    if (!raw) return;
    const d = JSON.parse(raw.textContent);
    const lines = [
        'BEGIN:VCARD',
        'VERSION:3.0',
        'FN:' + d.fn,
    ];
    if (d.title) lines.push('TITLE:' + d.title);
    if (d.org)   lines.push('ORG:' + d.org);
    if (d.tel)   lines.push('TEL;TYPE=CELL:+55' + d.tel);
    if (d.email) lines.push('EMAIL:' + d.email);
    if (d.url)   lines.push('URL:' + d.url);
    if (d.note)  lines.push('NOTE:' + d.note.replace(/\n/g, '\\n'));
    lines.push('END:VCARD');
    const vcf  = lines.join('\r\n');
    const blob = new Blob([vcf], { type: 'text/vcard;charset=utf-8' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = d.slug + '.vcf';
    a.click();
    setTimeout(() => URL.revokeObjectURL(a.href), 5000);
}
</script>

{{-- ── AGENDAR (Pro) ── --}}
@if ($card->schedule && $card->schedule->is_active)
<div class="cs" x-data="{ open: false }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="calendar" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Agendar horário
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body-flush" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div style="padding:0 0 12px;">
            <livewire:schedule.appointment-calendar :card="$card" />
        </div>
    </div>
</div>
@endif

{{-- ── FORMULÁRIO ── --}}
<div class="cs" x-data="{ open: false }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="message-square" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Enviar mensagem
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <livewire:card.contact-form :card="$card" />
    </div>
</div>

</div>{{-- /sections-wrap --}}

{{-- ═══════════════════ RODAPÉ / MARCA D'ÁGUA ═══════════════════ --}}
<div style="margin-top:8px;border-top:1px solid var(--ui-border);background:#EAE2B7;">
    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:{{ $card->show_watermark ? 'space-between' : 'center' }};">

        @if ($card->show_watermark)
        <span style="font-size:11px;color:#9c9585;font-weight:400;letter-spacing:.1px;">
            Criado com <strong style="color:#52514E;font-weight:700;">NEXOSN</strong>
        </span>
        @endif

        <a href="https://nexosn.pageup.net.br" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:5px;text-decoration:none;
                  opacity:.55;transition:opacity .2s;"
           onmouseenter="this.style.opacity='1'" onmouseleave="this.style.opacity='.55'"
           title="NEXOSN — Identidade digital">
            <svg viewBox="0 0 16 16" fill="none" style="width:11px;height:11px;flex-shrink:0;">
                <line x1="3" y1="3"  x2="3"  y2="13" stroke="#52514E" stroke-width="1.75" stroke-linecap="round"/>
                <line x1="3" y1="3"  x2="13" y2="13" stroke="#52514E" stroke-width="1.75" stroke-linecap="round"/>
                <line x1="13" y1="3" x2="13" y2="13" stroke="#52514E" stroke-width="1.75" stroke-linecap="round"/>
                <circle cx="3"  cy="3"  r="2" fill="#52514E"/>
                <circle cx="3"  cy="13" r="2" fill="#52514E"/>
                <circle cx="13" cy="3"  r="2" fill="#52514E"/>
                <circle cx="13" cy="13" r="2" fill="#52514E"/>
            </svg>
            <span style="font-family:'Inter',sans-serif;font-size:11px;font-weight:700;
                         color:#52514E;letter-spacing:.02em;line-height:1;">NEX<span style="opacity:.5;">OSN</span></span>
        </a>

    </div>
</div>

@endsection
