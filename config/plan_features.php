<?php

// Features controladas por CheckPlan que exigem plano Pro (ou trial ativo).
// Novos módulos/templates entram aqui — não editar App\Http\Middleware\CheckPlan.
return [
    'pro',
    'agenda',
    'messages',
    'campanha',
];
