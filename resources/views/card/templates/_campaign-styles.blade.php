<style>
    :root {
        --card-primary: {{ $card->primary_color }};
        --card-button:  {{ $card->button_color }};
        --ui-heading: #111827;
        --ui-text: #374151;
        --ui-label: #6b7280;
        --ui-border: rgba(0,0,0,0.08);
        --ui-bg: #f7f7f6;
        --ui-section-bg: #ffffff;
    }
    .sections-wrap { padding: 2px 14px 24px; display: flex; flex-direction: column; gap: 10px; }
    .cs { background: var(--ui-section-bg); border-radius: 16px; border: 1px solid var(--ui-border); padding: 16px; }
    .cs-title { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: var(--ui-heading); margin: 0 0 12px; text-transform: uppercase; letter-spacing: .03em; }
    .cs-title i { color: var(--card-primary); }

    /* Cards recolhíveis (clicar no título esconde/mostra o conteúdo) */
    .cs-toggle { background: none; border: none; padding: 0; margin: 0; width: 100%; font: inherit; text-align: left; cursor: pointer; }
    .cs-toggle .cs-chevron { margin-left: auto; color: var(--ui-label); transition: transform .2s ease; }
    .cs-collapsible.cs-collapsed .cs-toggle { margin-bottom: 0; }
    .cs-collapsible.cs-collapsed .cs-chevron { transform: rotate(-90deg); }
    .cs-collapsible .cs-body { overflow: hidden; }
    .cs-collapsible.cs-collapsed .cs-body { display: none; }

    /* Formulário de contato reaproveita o card padrão — remove o cabeçalho e o
       fundo/borda internos dele para não duplicar o título do card recolhível. */
    .cs-collapsible .cs-body .contact-form-heading { display: none; }
    .campaign-contact-form-wrap > div { background: transparent !important; border-top: none !important; padding: 0 !important; }

    /* Header */
    .cs-campaign-header { padding: 0; overflow: hidden; }
    .campaign-cover { height: 140px; background-size: cover; background-position: center; }
    .campaign-header-body { padding: 16px; text-align: center; }
    .campaign-portrait { width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; margin: -56px auto 10px; display: block; box-shadow: 0 4px 12px rgba(0,0,0,.12); }
    .campaign-name { font-size: 20px; font-weight: 800; color: var(--card-primary); margin: 0 0 4px; }
    .campaign-role { font-size: 13px; color: var(--ui-text); font-weight: 600; margin: 0 0 4px; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .campaign-ballot { color: #fff; font-weight: 800; border-radius: 999px; padding: 2px 10px; font-size: 12px; }
    .campaign-org { font-size: 12px; color: var(--ui-label); margin: 0 0 6px; }
    .campaign-slogan { font-size: 13px; font-style: italic; color: var(--ui-text); margin: 8px 0 0; }
    .campaign-countdown { text-align: center; padding: 10px; margin: 0 16px 16px; border-radius: 10px; background: var(--ui-bg); font-weight: 700; font-size: 13px; color: var(--card-primary); }

    /* Proposals */
    .campaign-proposal-group + .campaign-proposal-group { margin-top: 14px; }
    .campaign-proposal-category { font-size: 12px; font-weight: 700; color: var(--card-primary); margin: 0 0 8px; }
    .campaign-proposal-item + .campaign-proposal-item { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--ui-border); }
    .campaign-proposal-image { width: 100%; border-radius: 10px; margin-bottom: 8px; }
    .campaign-proposal-title { font-size: 14px; font-weight: 700; color: var(--ui-heading); margin: 0 0 4px; }
    .campaign-proposal-desc { font-size: 13px; color: var(--ui-text); margin: 0 0 6px; }
    .campaign-proposal-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; color: var(--card-primary); font-weight: 600; text-decoration: none; margin-right: 12px; }

    /* News */
    .campaign-news-item { display: flex; gap: 10px; }
    .campaign-news-item + .campaign-news-item { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--ui-border); }
    .campaign-news-image { width: 64px; height: 64px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
    .campaign-news-title { font-size: 13px; font-weight: 700; color: var(--ui-heading); margin: 0 0 2px; }
    .campaign-news-date { font-size: 11px; color: var(--ui-label); margin: 0 0 4px; }
    .campaign-news-body { font-size: 12px; color: var(--ui-text); margin: 0; }

    /* Timeline */
    .campaign-timeline-item { display: flex; gap: 10px; }
    .campaign-timeline-item + .campaign-timeline-item { margin-top: 14px; }
    .campaign-timeline-marker { width: 26px; height: 26px; border-radius: 50%; background: var(--card-primary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .campaign-timeline-date { font-size: 11px; color: var(--ui-label); margin: 0; }
    .campaign-timeline-title { font-size: 13px; font-weight: 700; color: var(--ui-heading); margin: 0 0 2px; }
    .campaign-timeline-desc { font-size: 12px; color: var(--ui-text); margin: 0; }

    /* Team */
    .campaign-team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .campaign-team-member { text-align: center; }
    .campaign-team-photo { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; margin: 0 auto 6px; }
    .campaign-team-photo-placeholder { background: var(--ui-bg); display: flex; align-items: center; justify-content: center; color: var(--ui-label); }
    .campaign-team-name { font-size: 12px; font-weight: 700; color: var(--ui-heading); margin: 0; }
    .campaign-team-role { font-size: 11px; color: var(--ui-label); margin: 0; }

    /* Events */
    .campaign-event-item { display: flex; gap: 12px; }
    .campaign-event-item + .campaign-event-item { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--ui-border); }
    .campaign-event-date { width: 44px; text-align: center; background: var(--ui-bg); border-radius: 8px; padding: 6px 0; flex-shrink: 0; }
    .campaign-event-day { display: block; font-size: 16px; font-weight: 800; color: var(--card-primary); }
    .campaign-event-month { display: block; font-size: 10px; text-transform: uppercase; color: var(--ui-label); }
    .campaign-event-title { font-size: 13px; font-weight: 700; color: var(--ui-heading); margin: 0 0 4px; }
    .campaign-event-meta { display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--ui-text); margin: 0 0 2px; }
    .campaign-event-desc { font-size: 12px; color: var(--ui-text); margin: 4px 0; }
    .campaign-event-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; color: var(--card-primary); font-weight: 600; text-decoration: none; }

    /* Files */
    .campaign-file-item { display: flex; align-items: center; gap: 8px; padding: 10px 0; text-decoration: none; color: var(--ui-text); font-size: 13px; font-weight: 600; }
    .campaign-file-item + .campaign-file-item { border-top: 1px solid var(--ui-border); }
    .campaign-file-item i:first-child { color: var(--card-primary); }
    .campaign-file-download-icon { margin-left: auto; color: var(--ui-label); }

    /* Rodapé legal */
    .campaign-legal-footer { padding: 4px 10px 10px; text-align: center; }
    .campaign-legal-footer p { font-size: 10px; color: var(--ui-label); line-height: 1.5; margin: 2px 0; }

    /* Links (compartilhado, estilo mínimo caso o card não use o template padrão) */
    .campaign-link-list { display: flex; flex-direction: column; gap: 8px; }
    .campaign-link-item { display: flex; align-items: center; gap: 10px; padding: 12px; border-radius: 12px; background: var(--ui-bg); text-decoration: none; color: var(--ui-heading); font-weight: 600; font-size: 13px; }
    .campaign-link-item i { color: var(--card-primary); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cs-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = btn.closest('.cs-collapsible');
            if (!card) return;
            var collapsed = card.classList.toggle('cs-collapsed');
            btn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
    });
});
</script>
