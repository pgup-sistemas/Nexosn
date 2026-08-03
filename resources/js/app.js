import './bootstrap';
import Sortable from 'sortablejs';

document.addEventListener('livewire:initialized', () => {
    initSortable();
    Livewire.hook('morph.updated', () => initSortable());
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            // Substitui o comportamento padrão do Livewire (confirm() nativo em
            // inglês + modal com o HTML cru da página de erro) por um aviso
            // amigável em português, consistente com o visual do painel.
            preventDefault();
            showSessionExpiredModal(status);
        });
    });
});

function showSessionExpiredModal(status) {
    if (document.getElementById('friendly-error-overlay')) return;

    const isSessionExpired = status === 419;
    const title = isSessionExpired ? 'Sua sessão expirou' : 'Ops! Algo deu errado';
    const message = isSessionExpired
        ? 'Por segurança, sua sessão foi encerrada por inatividade. Atualize a página para continuar de onde parou.'
        : `Não foi possível concluir a ação agora (erro ${status}). Atualize a página e tente novamente.`;

    const overlay = document.createElement('div');
    overlay.id = 'friendly-error-overlay';
    overlay.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;padding:16px;font-family:Inter,ui-sans-serif,system-ui,sans-serif;';
    overlay.innerHTML = `
        <div style="background:#fff;border-radius:16px;max-width:380px;width:100%;padding:28px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.3);">
            <div style="width:48px;height:48px;border-radius:9999px;background:#FFF4E5;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D62828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
            </div>
            <h2 style="font-size:16px;font-weight:600;color:#1a1f2e;margin:0 0 8px;">${title}</h2>
            <p style="font-size:13px;color:#666;line-height:1.5;margin:0 0 20px;">${message}</p>
            <button id="friendly-error-reload" style="width:100%;background:#003049;color:#fff;font-size:14px;font-weight:600;border:none;border-radius:10px;padding:12px;cursor:pointer;">
                Atualizar página
            </button>
        </div>
    `;
    document.body.appendChild(overlay);
    document.getElementById('friendly-error-reload').addEventListener('click', () => window.location.reload());
}

function initSortable() {
    const links = document.getElementById('sortable-links');
    if (links && !links._sortable) {
        links._sortable = Sortable.create(links, {
            handle: '[data-lucide="grip-vertical"]',
            animation: 150,
            onEnd() {
                const order = [...links.querySelectorAll('[data-id]')].map(e => e.dataset.id);
                Livewire.dispatch('reorder-links', { order });
            },
        });
    }

    const photos = document.getElementById('sortable-photos');
    if (photos && !photos._sortable) {
        photos._sortable = Sortable.create(photos, {
            animation: 150,
            onEnd() {
                const order = [...photos.querySelectorAll('[data-id]')].map(e => e.dataset.id);
                Livewire.dispatch('reorder-photos', { order });
            },
        });
    }
}
