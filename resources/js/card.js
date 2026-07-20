// Importa todos os ícones porque $link->lucide_icon é dinâmico (vem do banco).
// O bundle (~87KB gz) é cacheado pelo Service Worker após o primeiro carregamento.
import { createIcons, icons } from 'lucide';

// Inicializa ícones Lucide (substitui CDN unpkg)
function initIcons() {
    createIcons({ icons });
}

document.addEventListener('DOMContentLoaded', initIcons);
document.addEventListener('livewire:navigated', initIcons);
document.addEventListener('livewire:updated', initIcons);

// ── Registro do Service Worker ──
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

// ── Banner de status offline ──
(function () {
    const BANNER_ID = 'nexosn-offline-banner';

    function createBanner() {
        if (document.getElementById(BANNER_ID)) return;
        const el = document.createElement('div');
        el.id = BANNER_ID;
        el.style.cssText = [
            'position:fixed', 'top:0', 'left:0', 'right:0', 'z-index:9999',
            'background:#F77F00', 'color:#fff', 'font-size:12px', 'font-weight:600',
            'text-align:center', 'padding:8px 16px',
            'font-family:Inter,sans-serif', 'letter-spacing:.01em',
        ].join(';');
        el.textContent = '📶 Você está offline — dados podem estar desatualizados';
        document.body.prepend(el);
    }

    function removeBanner() {
        const el = document.getElementById(BANNER_ID);
        if (el) el.remove();
    }

    if (!navigator.onLine) createBanner();
    window.addEventListener('offline', createBanner);
    window.addEventListener('online', removeBanner);
})();
