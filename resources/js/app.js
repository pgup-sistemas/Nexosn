import './bootstrap';
import Sortable from 'sortablejs';

document.addEventListener('livewire:initialized', () => {
    initSortable();
    Livewire.hook('morph.updated', () => initSortable());
});

function initSortable() {
    const el = document.getElementById('sortable-links');
    if (!el || el._sortable) return;

    el._sortable = Sortable.create(el, {
        handle: '[data-lucide="grip-vertical"]',
        animation: 150,
        onEnd() {
            const order = [...el.querySelectorAll('[data-id]')].map(e => e.dataset.id);
            Livewire.dispatch('reorder-links', { order });
        },
    });
}
