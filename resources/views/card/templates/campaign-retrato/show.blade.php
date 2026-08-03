@extends('layouts.card')

@section('title', $card->campaignProfile?->campaign_name ?: $card->display_name)
@section('description', $card->campaignProfile?->slogan ?: 'Cartão de campanha de ' . $card->display_name . ' na NEXOSN.')
@section('og_image', $card->campaignProfile?->portrait_photo
    ? \Illuminate\Support\Facades\Storage::url($card->campaignProfile->portrait_photo)
    : ($card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png')))

@section('card_colors')
    @include('card.templates._campaign-styles')
    <style>
        /* Retrato: sem capa, retrato grande e central, sem margem negativa */
        .campaign-tpl-retrato .campaign-cover { display: none; }
        .campaign-tpl-retrato .campaign-portrait { width: 148px; height: 148px; margin: 8px auto 12px; border-width: 6px; }
        .campaign-tpl-retrato .campaign-name { font-size: 24px; }
    </style>
@endsection

@section('content')
<div class="sections-wrap campaign-tpl-retrato">
    <x-card.section.header :card="$card" />
    <x-card.section.proposals :card="$card" />
    <x-card.section.events :card="$card" />
    <x-card.section.timeline :card="$card" />
    <x-card.section.team :card="$card" />
    <x-card.section.news :card="$card" />
    <x-card.section.links :card="$card" />
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
