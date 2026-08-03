@extends('layouts.card')

@section('title', $card->campaignProfile?->campaign_name ?: $card->display_name)
@section('description', $card->campaignProfile?->slogan ?: 'Cartão de campanha de ' . $card->display_name . ' na NEXOSN.')
@section('og_image', $card->campaignProfile?->portrait_photo
    ? \Illuminate\Support\Facades\Storage::url($card->campaignProfile->portrait_photo)
    : ($card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png')))

@section('card_colors')
    @include('card.templates._campaign-styles')
    <style>
        /* Retrato ("santinho digital"): foto grande, retangular, de corpo/rosto em
           destaque ocupando a largura do cartão — não é avatar circular. */
        .campaign-tpl-retrato .campaign-cover { display: none; }
        .campaign-tpl-retrato .campaign-portrait {
            width: calc(100% + 32px);
            height: auto;
            aspect-ratio: 3 / 4;
            object-fit: cover;
            object-position: top center;
            margin: -16px -16px 14px -16px;
            border-radius: 0;
            border: none;
            box-shadow: none;
            display: block;
        }
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
