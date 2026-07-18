@extends('layouts.card')

@section('title', $card->display_name)
@section('description', $card->bio ? \Illuminate\Support\Str::limit(strip_tags($card->bio), 160) : 'Cartão de visita digital.')

@section('og_image', $card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png'))

@section('card_colors')
<style>
    :root {
        --card-primary: {{ $card->primary_color }};
        --card-button:  {{ $card->button_color }};
    }
</style>
@endsection

@section('content')

{{-- ═══════════════════ HEADER / CAPA ═══════════════════ --}}
<header class="relative">

    {{-- Foto de capa --}}
    <div class="h-[110px] w-full overflow-hidden"
         style="background-color: var(--card-primary);">
        @if ($card->cover_photo)
            <img src="{{ Storage::url($card->cover_photo) }}"
                 alt="Capa de {{ $card->display_name }}"
                 class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 h-[110px]" style="background: rgba(0,0,0,0.15);"></div>
    </div>

    {{-- Avatar --}}
    <div class="absolute left-1/2 -translate-x-1/2"
         style="top: 82px;">
        <div class="w-[56px] h-[56px] rounded-full border-[3px] border-white overflow-hidden shadow-md"
             style="background-color: var(--card-primary);">
            @if ($card->profile_photo)
                <img src="{{ Storage::url($card->profile_photo) }}"
                     alt="{{ $card->display_name }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($card->display_name, 0, 1)) }}
                </div>
            @endif
        </div>
    </div>

</header>

{{-- ═══════════════════ IDENTIDADE ═══════════════════ --}}
<div class="pt-10 pb-4 px-5 text-center">

    @if ($card->logo)
        <img src="{{ Storage::url($card->logo) }}"
             alt="Logo {{ $card->company }}"
             class="h-8 mx-auto mb-3 object-contain">
    @endif

    <h1 class="text-lg font-bold text-gray-900 leading-tight">{{ $card->display_name }}</h1>

    @if ($card->title || $card->company)
        <p class="text-sm text-gray-500 mt-0.5">
            {{ implode(' · ', array_filter([$card->title, $card->company])) }}
        </p>
    @endif

</div>

{{-- ═══════════════════ LINKS PRINCIPAIS ═══════════════════ --}}
@php
    $socialTypes = ['instagram.com','wa.me','whatsapp','linkedin.com','tiktok.com','youtube.com','twitter.com','x.com','facebook.com','t.me','telegram','pinterest.com','spotify.com'];
    $socialLinks = $card->links->filter(fn ($l) => collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
    $customLinks = $card->links->filter(fn ($l) => !collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
@endphp

{{-- Ícones de redes sociais --}}
@if ($socialLinks->isNotEmpty())
<div class="flex justify-center gap-3 px-5 pb-4 flex-wrap">
    @foreach ($socialLinks as $link)
        <a href="{{ $link->url }}" target="_blank" rel="noopener"
           class="w-9 h-9 rounded-full flex items-center justify-center transition hover:opacity-80"
           style="background-color: var(--card-primary);"
           title="{{ $link->label }}">
            <i data-lucide="{{ $link->lucide_icon }}" class="w-[18px] h-[18px]" style="color: #EAE2B7;"></i>
        </a>
    @endforeach
</div>
@endif

{{-- Links customizados --}}
@if ($customLinks->isNotEmpty())
<div class="px-4 pb-2 space-y-2">
    @foreach ($customLinks as $link)
        <a href="{{ $link->url }}" target="_blank" rel="noopener"
           class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-sm font-medium text-white transition hover:opacity-90 active:scale-[0.98]"
           style="background-color: var(--card-button);">
            <i data-lucide="{{ $link->lucide_icon }}" class="w-4 h-4 shrink-0"></i>
            <span>{{ $link->label }}</span>
        </a>
    @endforeach
</div>
@endif

{{-- ═══════════════════ BIO ═══════════════════ --}}
@if ($card->bio)
<div class="px-5 py-4 border-t border-gray-100">
    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $card->bio }}</p>
</div>
@endif

{{-- ═══════════════════ CONTATO ═══════════════════ --}}
@if ($card->contact_phone || $card->contact_email || $card->website || $card->address)
<div class="px-4 py-4 border-t border-gray-100 space-y-2">

    @if ($card->contact_phone)
        <a href="tel:{{ preg_replace('/\D/', '', $card->contact_phone) }}"
           class="flex items-center gap-3 text-sm text-gray-700 hover:text-gray-900">
            <i data-lucide="phone" class="w-4 h-4 shrink-0" style="color: var(--card-primary);"></i>
            <span>{{ $card->contact_phone }}</span>
        </a>
    @endif

    @if ($card->contact_email)
        <a href="mailto:{{ $card->contact_email }}"
           class="flex items-center gap-3 text-sm text-gray-700 hover:text-gray-900">
            <i data-lucide="mail" class="w-4 h-4 shrink-0" style="color: var(--card-primary);"></i>
            <span>{{ $card->contact_email }}</span>
        </a>
    @endif

    @if ($card->website)
        <a href="{{ $card->website }}" target="_blank" rel="noopener"
           class="flex items-center gap-3 text-sm text-gray-700 hover:text-gray-900">
            <i data-lucide="globe" class="w-4 h-4 shrink-0" style="color: var(--card-primary);"></i>
            <span>{{ parse_url($card->website, PHP_URL_HOST) ?? $card->website }}</span>
        </a>
    @endif

    @if ($card->address)
        <div class="flex items-start gap-3 text-sm text-gray-700">
            <i data-lucide="map-pin" class="w-4 h-4 shrink-0 mt-0.5" style="color: var(--card-primary);"></i>
            <span>{{ $card->address }}</span>
        </div>
    @endif

</div>
@endif

{{-- ═══════════════════ PIX ═══════════════════ --}}
@if ($card->pix_key)
<div class="px-4 pb-4">
    <button onclick="copiarPix()"
            class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl text-sm font-medium text-white transition hover:opacity-90 active:scale-[0.98]"
            style="background-color: var(--card-button);">
        <i data-lucide="qr-code" class="w-4 h-4"></i>
        <span>Pagar via PIX</span>
    </button>
    <div id="pix-copiado" class="hidden mt-2 text-center text-xs text-green-600 font-medium">
        ✓ Chave PIX copiada!
    </div>
</div>

<script>
function copiarPix() {
    navigator.clipboard.writeText('{{ $card->pix_key }}').then(() => {
        const el = document.getElementById('pix-copiado');
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 3000);
    });
}
</script>
@endif

{{-- ═══════════════════ GALERIA DE FOTOS ═══════════════════ --}}
@if ($card->photos->isNotEmpty())
<div class="px-4 pb-4 border-t border-gray-100 pt-4">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Galeria</p>
    <div class="grid grid-cols-3 gap-1.5">
        @foreach ($card->photos as $photo)
            <a href="{{ $photo->url }}" target="_blank"
               class="aspect-square rounded-lg overflow-hidden bg-gray-100 block">
                <img src="{{ $photo->thumbnail_url }}"
                     alt="{{ $photo->caption ?? 'Foto' }}"
                     class="w-full h-full object-cover hover:opacity-90 transition">
            </a>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════ BOTÃO SALVAR CONTATO (vCard) ═══════════════════ --}}
<div class="px-4 pb-4 pt-2">
    <a href="{{ route('card.vcard', $card->slug) }}"
       class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl text-sm font-medium border border-gray-200 text-gray-700 transition hover:bg-gray-50 active:scale-[0.98]">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Salvar contato</span>
    </a>
</div>

{{-- ═══════════════════ MARCA D'ÁGUA ═══════════════════ --}}
@if ($card->show_watermark)
<div class="py-4 text-center border-t border-gray-100">
    <a href="{{ url('/') }}" target="_blank"
       class="text-xs text-gray-400 hover:text-gray-600 transition">
        Criado com <span class="font-semibold" style="color: var(--card-primary);">Card</span>
    </a>
</div>
@endif

@endsection
