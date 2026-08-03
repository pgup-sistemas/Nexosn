<?php

// Registro central de templates do cartão público. Cada novo template
// (padrão, escuro, campanha, futuros nichos) entra aqui em vez de um
// if/else no controller — ver docs/auditoria-template-campanha.md §7.1.
return [
    'default' => [
        'label' => 'Padrão',
        'view' => 'card.show',
        'requires_profile' => null,
        'min_plan' => 'free',
    ],

    'dark' => [
        'label' => 'Escuro',
        'view' => 'card.show-dark',
        'requires_profile' => null,
        'min_plan' => 'free',
    ],

    'campaign-hero' => [
        'label' => 'Campanha — Hero',
        'view' => 'card.templates.campaign-hero.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],

    'campaign-institucional' => [
        'label' => 'Campanha — Institucional',
        'view' => 'card.templates.campaign-institucional.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],

    'campaign-retrato' => [
        'label' => 'Campanha — Retrato',
        'view' => 'card.templates.campaign-retrato.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],

    'campaign-banner' => [
        'label' => 'Campanha — Banner',
        'view' => 'card.templates.campaign-banner.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],

    'campaign-minimalista' => [
        'label' => 'Campanha — Minimalista',
        'view' => 'card.templates.campaign-minimalista.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],

    'campaign-chapa' => [
        'label' => 'Campanha — Chapa',
        'view' => 'card.templates.campaign-chapa.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],

    'campaign-moderno' => [
        'label' => 'Campanha — Moderno',
        'view' => 'card.templates.campaign-moderno.show',
        'requires_profile' => 'campaign',
        'min_plan' => 'pro',
    ],
];
