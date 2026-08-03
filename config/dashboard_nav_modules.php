<?php

// Itens do menu lateral do dashboard, agrupados por "módulo de perfil"
// (config/card_templates.php → requires_profile). Um módulo pode ser
// consumido por vários templates visuais diferentes — por isso o menu
// reage ao módulo, não ao template específico selecionado.
//
// Para adicionar um novo nicho (ex: imobiliário) no futuro: registre os
// templates dele em card_templates.php com requires_profile => 'imobiliario'
// e adicione uma entrada aqui — nenhuma outra view precisa ser tocada.
return [
    'campaign' => [
        'label' => 'Campanha',
        'items' => [
            ['route' => 'dashboard.campaign.profile',   'icon' => 'vote',          'label' => 'Perfil de Campanha'],
            ['route' => 'dashboard.campaign.proposals', 'icon' => 'list-checks',   'label' => 'Propostas'],
            ['route' => 'dashboard.campaign.news',      'icon' => 'newspaper',     'label' => 'Notícias'],
            ['route' => 'dashboard.campaign.timeline',  'icon' => 'history',       'label' => 'Linha do Tempo'],
            ['route' => 'dashboard.campaign.team',      'icon' => 'users',         'label' => 'Equipe / Chapa'],
            ['route' => 'dashboard.campaign.events',    'icon' => 'calendar-days', 'label' => 'Agenda de Eventos'],
            ['route' => 'dashboard.campaign.files',     'icon' => 'folder-down',   'label' => 'Arquivos'],
        ],
    ],
];
