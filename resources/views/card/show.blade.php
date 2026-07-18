@extends('layouts.card')

@section('title', $card->display_name)
@section('description', $card->bio ?? 'Cartão de visita digital.')

@section('card_colors')
<style>
    :root {
        --card-primary: {{ $card->brand_color_primary ?? '#003049' }};
        --card-button:  {{ $card->brand_color_button  ?? '#D62828' }};
    }
</style>
@endsection

@section('content')
    <p class="p-8 text-center text-gray-500">Cartão em construção — Fase 2</p>
@endsection
