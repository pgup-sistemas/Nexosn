@extends('layouts.card')

@section('title', $card->campaignProfile?->campaign_name ?: $card->display_name)
@section('description', $card->campaignProfile?->slogan ?: 'Cartão de campanha de ' . $card->display_name . ' na NEXOSN.')
@section('og_image', $card->campaignProfile?->portrait_photo
    ? \Illuminate\Support\Facades\Storage::url($card->campaignProfile->portrait_photo)
    : ($card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png')))

@section('card_colors')
    @include('card.templates._campaign-styles')
    <style>
        /* Moderno: cabeçalho em gradiente, seções com cantos bem arredondados */
        .campaign-tpl-moderno .cs-campaign-header { background: linear-gradient(160deg, var(--card-primary), var(--card-button)); }
        .campaign-tpl-moderno .campaign-name,
        .campaign-tpl-moderno .campaign-role,
        .campaign-tpl-moderno .campaign-org,
        .campaign-tpl-moderno .campaign-slogan { color: #fff; }
        .campaign-tpl-moderno .campaign-ballot { background: rgba(255,255,255,.25) !important; }
        .campaign-tpl-moderno .cs { border-radius: 22px; }
        .campaign-tpl-moderno .campaign-countdown { background: rgba(255,255,255,.15); color: #fff; margin: 0 16px 20px; }
    </style>
@endsection

@section('content')
<div class="sections-wrap campaign-tpl-moderno">
    <x-card.section.header :card="$card" />
    <x-card.section.proposals :card="$card" />
    <x-card.section.events :card="$card" />
    <x-card.section.team :card="$card" />
    <x-card.section.timeline :card="$card" />
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
