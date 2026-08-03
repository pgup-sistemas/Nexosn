@extends('layouts.card')

@section('title', $card->campaignProfile?->campaign_name ?: $card->display_name)
@section('description', $card->campaignProfile?->slogan ?: 'Cartão de campanha de ' . $card->display_name . ' na NEXOSN.')
@section('og_image', $card->campaignProfile?->portrait_photo
    ? \Illuminate\Support\Facades\Storage::url($card->campaignProfile->portrait_photo)
    : ($card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png')))

@section('card_colors')
    @include('card.templates._campaign-styles')
    <style>
        /* Minimalista: sem capa, cores reduzidas, tipografia enxuta, sem bordas/sombra nas seções */
        .campaign-tpl-minimalista .campaign-cover { display: none; }
        .campaign-tpl-minimalista .cs { border: none; border-bottom: 1px solid var(--ui-border); border-radius: 0; padding: 14px 4px; }
        .campaign-tpl-minimalista .campaign-portrait { border-width: 2px; box-shadow: none; }
        .campaign-tpl-minimalista .campaign-ballot { background: transparent !important; color: var(--card-primary); border: 1px solid var(--card-primary); }
        .campaign-tpl-minimalista .cs-title { text-transform: none; letter-spacing: normal; font-weight: 600; }
    </style>
@endsection

@section('content')
<div class="sections-wrap campaign-tpl-minimalista">
    <x-card.section.header :card="$card" />
    <x-card.section.proposals :card="$card" />
    <x-card.section.links :card="$card" />
    <x-card.section.events :card="$card" />
    <x-card.section.news :card="$card" />
    <x-card.section.timeline :card="$card" />
    <x-card.section.team :card="$card" />
    <x-card.section.files :card="$card" />
    <x-card.section.contact-form :card="$card" :purposeOptions="[
        'voluntario' => 'Quero ser voluntário(a)',
        'apoiador' => 'Quero apoiar',
        'sugestao' => 'Sugestão',
        'contato' => 'Outro assunto',
    ]" />
    <x-card.section.legal-footer :card="$card" />
</div>
@endsection
