@extends('layouts.card')

@section('title', $card->display_name)
@section('description', $card->bio
    ? \Illuminate\Support\Str::limit(strip_tags($card->bio), 155)
    : ($card->title ? $card->display_name . ' — ' . $card->title . ($card->company ? ' · ' . $card->company : '') . ' | Identidade digital na NEXOSN.' : 'Identidade digital de ' . $card->display_name . ' na plataforma NEXOSN.')
)

@section('og_image', $card->profile_photo ? \Illuminate\Support\Facades\Storage::url($card->profile_photo) : asset('images/og-default.png'))

@section('card_colors')
<style>
    :root {
        --card-primary:    {{ $card->primary_color }};
        --card-button:     {{ $card->button_color }};
        --card-shell-bg:   #0B0B0D;
        --dk-bg:           #0B0B0D;
        --dk-surf1:     #18181C;
        --dk-surf2:     #222228;
        --dk-surf3:     #30303A;
        --dk-border:    rgba(255,255,255,0.08);
        --dk-border-md: rgba(255,255,255,0.13);
        --dk-gold:      #D4B478;
        --dk-gold-dim:  rgba(212,180,120,0.13);
        --dk-gold-ring: rgba(212,180,120,0.28);
        --dk-text:      #F4F4F8;
        --dk-text-md:   #AEAEBB;
        --dk-text-muted:#6A6A7A;
        --dk-tap:       rgba(255,255,255,0.05);
    }

    [x-cloak] { display: none !important; }

    * { -webkit-tap-highlight-color: transparent; }

    /* ── Layout base ── */
    body { background: var(--dk-bg); }

    /* ── Seção flat (sem card box) ── */
    .dk-section {
        padding: 24px 22px 0;
    }
    .dk-section-label {
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #8A8A9A;
        margin-bottom: 14px;
    }

    /* ── Divider hairline ── */
    .dk-rule {
        height: 1px;
        background: var(--dk-border);
        margin: 0 22px;
    }

    /* ── Linhas de contato ── */
    .dk-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--dk-border);
        text-decoration: none;
        cursor: pointer;
        transition: opacity .12s;
    }
    .dk-row:last-child { border-bottom: none; }
    .dk-row:active { opacity: .65; }

    .dk-row-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: var(--dk-gold-dim);
        border: 1px solid var(--dk-gold-ring);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dk-row-info { flex: 1; min-width: 0; }
    .dk-row-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: var(--dk-text-muted);
        line-height: 1;
    }
    .dk-row-value {
        font-size: 14px;
        color: #c8c8d8;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .dk-arrow {
        flex-shrink: 0;
        opacity: .2;
    }

    /* ── Links customizados ── */
    .dk-link {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px 0;
        border-bottom: 1px solid var(--dk-border);
        text-decoration: none;
        transition: opacity .12s;
    }
    .dk-link:last-child { border-bottom: none; }
    .dk-link:active { opacity: .6; }
    .dk-link-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--dk-gold);
        flex-shrink: 0;
        opacity: .45;
    }
    .dk-link-name {
        font-size: 14px;
        font-weight: 500;
        color: #ccccd8;
        flex: 1;
    }

    /* ── Redes sociais ── */
    .dk-social-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 13px;
        border-radius: 99px;
        border: 1px solid var(--dk-border-md);
        background: var(--dk-tap);
        font-size: 12px;
        font-weight: 600;
        color: var(--dk-text-md);
        text-decoration: none;
        white-space: nowrap;
        transition: border-color .12s, color .12s;
        flex-shrink: 0;
    }
    .dk-social-chip:active { opacity: .65; }

    /* ── Serviços ── */
    .dk-svc {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid var(--dk-border);
        cursor: pointer;
        transition: opacity .12s;
        width: 100%;
        text-align: left;
        background: none;
        border-left: none;
        border-right: none;
        border-top: none;
    }
    .dk-svc:last-child { border-bottom: none; }
    .dk-svc:active { opacity: .65; }
    .dk-svc-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: var(--dk-gold-dim);
        border: 1px solid var(--dk-gold-ring);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dk-svc-info { flex: 1; min-width: 0; }
    .dk-svc-name { font-size: 14.5px; font-weight: 600; color: #e0e0ee; line-height: 1.3; }
    .dk-svc-desc { font-size: 12px; color: var(--dk-text-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dk-svc-price { font-size: 15px; font-weight: 700; color: var(--dk-gold); white-space: nowrap; flex-shrink: 0; }

    /* ── Botão PIX ── */
    .dk-pix-btn {
        width: 100%;
        padding: 15px 20px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(100deg, #7A5520 0%, #C9A96E 55%, #E8C98A 100%);
        color: #1a0e00;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        cursor: pointer;
        letter-spacing: .2px;
        box-shadow: 0 4px 28px rgba(201,169,110,0.18);
        transition: opacity .15s, transform .12s;
    }
    .dk-pix-btn:active { transform: scale(.98); opacity: .88; }

    /* ── Action pills ── */
    .dk-action-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 99px;
        border: 1px solid var(--dk-border-md);
        background: var(--dk-tap);
        font-size: 12px;
        font-weight: 600;
        color: var(--dk-text-md);
        letter-spacing: .3px;
        cursor: pointer;
        text-decoration: none;
        transition: border-color .15s;
        min-height: 44px;
    }
    .dk-action-pill:active { opacity: .7; }

    /* ── Galeria ── */
    .dk-gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3px;
    }
    .dk-gallery-cell {
        aspect-ratio: 1;
        overflow: hidden;
        background: var(--dk-surf2);
        border: none;
        padding: 0;
        cursor: zoom-in;
        position: relative;
    }

    /* ── Localização ── */
    .dk-map-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 10px;
        background: var(--dk-surf1);
        border: 1px solid var(--dk-border);
        text-decoration: none;
        margin-bottom: 10px;
    }
    .dk-map-actions {
        display: flex;
        gap: 8px;
    }
    .dk-map-action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 11px;
        min-height: 44px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid var(--dk-border);
        background: var(--dk-surf1);
        color: var(--dk-text-muted);
        text-decoration: none;
        cursor: pointer;
    }

    /* ── Formulário ── */
    .dk-input {
        width: 100%;
        background: var(--dk-surf1);
        border: 1px solid var(--dk-border-md);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13px;
        color: #b8b8c8;
        font-family: inherit;
        outline: none;
        transition: border-color .15s;
        margin-bottom: 8px;
    }
    .dk-input::placeholder { color: var(--dk-text-muted); }
    .dk-input:focus { border-color: var(--dk-gold-ring); }
    .dk-textarea {
        width: 100%;
        background: var(--dk-surf1);
        border: 1px solid var(--dk-border-md);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13px;
        color: #b8b8c8;
        height: 90px;
        resize: none;
        font-family: inherit;
        margin-bottom: 8px;
        outline: none;
        transition: border-color .15s;
    }
    .dk-textarea::placeholder { color: var(--dk-text-muted); }
    .dk-textarea:focus { border-color: var(--dk-gold-ring); }
    .dk-form-submit {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 10px;
        background: var(--dk-gold-dim);
        color: var(--dk-gold);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid var(--dk-gold-ring);
        transition: background .15s;
    }
    .dk-form-submit:active { opacity: .8; }

    /* ── Agendar ── */
    .dk-schedule-btn {
        width: 100%;
        padding: 14px 20px;
        border: 1px solid var(--dk-border-md);
        border-radius: 14px;
        background: var(--dk-surf1);
        color: var(--dk-text-muted);
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        min-height: 50px;
    }

    /* ── QR / Compartilhar ── */
    .dk-share-link-box {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--dk-surf1);
        border: 1px solid var(--dk-border);
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 10px;
    }
    .dk-share-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 12px;
        min-height: 44px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid var(--dk-border);
        background: var(--dk-surf1);
        color: var(--dk-text-muted);
        cursor: pointer;
    }

    /* ── Modal PIX (reutiliza ids do default, estilos dark) ── */
    .pix-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 9998;
        background: rgba(0,0,0,0.72); backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .pix-modal-sheet {
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 9999;
        background: #141416; border-radius: 24px 24px 0 0;
        padding: 20px 20px 36px; max-width: 480px; margin: 0 auto;
        transform: translateY(100%);
        transition: transform .32s cubic-bezier(.32,1,.24,1);
        max-height: 92vh; overflow-y: auto;
        border-top: 1px solid rgba(201,169,110,0.15);
    }
    .pix-modal-sheet.open { transform: translateY(0); }
    .pix-modal-handle {
        width: 36px; height: 4px; border-radius: 99px;
        background: #2a2a38; margin: 0 auto 18px;
    }
    .pix-modal-title { font-size: 16px; font-weight: 700; color: #F0F0F4; text-align: center; margin-bottom: 4px; }
    .pix-modal-price { font-size: 28px; font-weight: 800; color: var(--dk-gold); text-align: center; letter-spacing: -.5px; margin: 6px 0 18px; }
    .pix-qr-wrap { display: flex; justify-content: center; margin-bottom: 16px; }
    .pix-qr-wrap svg { max-width: 220px; border-radius: 12px; background: #fff; padding: 8px; }
    .pix-payload-box {
        background: var(--dk-surf2); border: 1px solid var(--dk-border); border-radius: 12px;
        padding: 11px 14px; display: flex; align-items: center; gap: 8px; margin-bottom: 14px;
    }
    .pix-payload-text { flex: 1; font-size: 11px; color: var(--dk-text-muted); word-break: break-all; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pix-copy-btn {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 14px; border-radius: 14px;
        background: linear-gradient(100deg, #7A5520 0%, #C9A96E 55%, #E8C98A 100%);
        color: #1a0e00; font-size: 14px; font-weight: 700;
        border: none; cursor: pointer; transition: opacity .15s;
    }
    .pix-copy-btn:active { opacity: .85; }
    .pix-feedback { text-align: center; font-size: 12px; color: #4ade80; font-weight: 600; margin-top: 8px; min-height: 18px; }
    .pix-spinner {
        width: 36px; height: 36px; border: 3px solid rgba(255,255,255,0.08);
        border-top-color: var(--dk-gold); border-radius: 50%;
        animation: pix-spin .7s linear infinite; margin: 40px auto;
    }
    @keyframes pix-spin { to { transform: rotate(360deg); } }

    /* ── Lightbox ── */
    #dk-lightbox {
        display: none; position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,0.96); flex-direction: column;
        align-items: center; justify-content: center;
    }

    /* ── Formulário de contato (override do componente Livewire) ── */
    #dk-form-wrap > div {
        background: var(--dk-surf1) !important;
        border-top-color: var(--dk-border) !important;
    }
    #dk-form-wrap .bg-gray-50  { background: var(--dk-surf1) !important; }
    #dk-form-wrap .border-gray-100 { border-color: var(--dk-border) !important; }
    #dk-form-wrap input,
    #dk-form-wrap textarea {
        background: var(--dk-surf2) !important;
        border-color: rgba(255,255,255,0.1) !important;
        color: #b8b8c8 !important;
        font-family: inherit;
    }
    #dk-form-wrap input::placeholder,
    #dk-form-wrap textarea::placeholder { color: var(--dk-text-muted) !important; }
    #dk-form-wrap input:focus,
    #dk-form-wrap textarea:focus {
        border-color: rgba(201,169,110,0.4) !important;
        outline: none;
    }
    #dk-form-wrap .flex.items-center.gap-2.text-sm {
        color: var(--dk-gold) !important;
    }
    #dk-form-wrap .text-gray-800 { color: #F0F0F4 !important; }
    #dk-form-wrap .text-gray-500 { color: var(--dk-text-muted) !important; }
    #dk-form-wrap .text-xs.text-red-600 { color: #f87171 !important; }
    #dk-form-wrap .bg-amber-50 { background: rgba(251,191,36,0.08) !important; border-color: rgba(251,191,36,0.25) !important; }
    #dk-form-wrap .text-amber-800 { color: #fbbf24 !important; }
    #dk-form-wrap .bg-green-100 { background: rgba(74,222,128,0.1) !important; }
    #dk-form-wrap .text-green-600 { color: #4ade80 !important; }
    #dk-form-wrap .text-green-600[data-lucide] { stroke: #4ade80 !important; }

    /* ── Rodapé ── */
    .dk-footer {
        padding: 28px 24px 32px;
        text-align: center;
        border-top: 1px solid var(--dk-border);
    }
    .dk-footer-brand {
        font-size: 9px; font-weight: 700; letter-spacing: 3px;
        text-transform: uppercase; color: var(--dk-surf3);
    }

    /* ── Cookie banner (dark) ── */
    #cookie-banner-dk {
        display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 9990;
        background: #0d0d10; padding: 14px 16px 20px;
        box-shadow: 0 -4px 24px rgba(0,0,0,.6);
        border-top: 1px solid var(--dk-border-md);
    }

    /* ── Scroll do social ── */
    .dk-social-track {
        display: flex; gap: 7px; overflow-x: auto;
        scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
        scrollbar-width: none; cursor: grab; user-select: none;
        padding-bottom: 2px;
    }
    .dk-social-track:active { cursor: grabbing; }
    .dk-social-track::-webkit-scrollbar { display: none; }

    /* ── Accordion suave ── */
    .dk-accordion-btn {
        width: 100%; background: none; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 0; gap: 10px; text-align: left;
    }
    .dk-chevron {
        width: 15px; height: 15px; flex-shrink: 0;
        color: var(--dk-text-muted); transition: transform .22s ease;
        opacity: .5;
    }
</style>
@endsection

@section('content')

@php
    $socialTypes = ['instagram.com','wa.me','whatsapp','linkedin.com','tiktok.com','youtube.com','twitter.com','x.com','facebook.com','t.me','telegram','pinterest.com','spotify.com'];
    $socialLinks = $card->links->filter(fn ($l) => $l->is_active && collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
    $customLinks = $card->links->filter(fn ($l) => $l->is_active && !collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
    $activeServices = $card->services->where('is_active', true);
@endphp

{{-- ═══ COVER ═══ --}}
<div style="height:190px;width:100%;overflow:hidden;position:relative;background:linear-gradient(160deg,#1a1018 0%,#0d1520 55%,#0B0B0D 100%);">
    @if ($card->cover_photo)
        <img src="{{ Storage::url($card->cover_photo) }}"
             alt="Capa de {{ $card->display_name }}"
             style="width:100%;height:100%;object-fit:cover;object-position:center;display:block;">
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.1) 0%,rgba(11,11,13,1) 100%);"></div>
</div>

{{-- ═══ AVATAR + IDENTIDADE ═══ --}}
<div style="display:flex;flex-direction:column;align-items:center;margin-top:-48px;padding:0 20px;position:relative;z-index:2;">

    {{-- Avatar --}}
    <div style="position:relative;">
        <div style="width:96px;height:96px;border-radius:50%;overflow:hidden;
                    background:linear-gradient(135deg,#1e1e24,#2a2a32);
                    border:2px solid rgba(201,169,110,0.3);
                    box-shadow:0 0 0 4px #0B0B0D,0 8px 32px rgba(0,0,0,.6);">
            @if ($card->profile_photo)
                <img src="{{ Storage::url($card->profile_photo) }}"
                     alt="{{ $card->display_name }}"
                     style="width:100%;height:100%;object-fit:cover;object-position:top center;display:block;">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;
                            font-family:Georgia,serif;font-size:34px;font-weight:400;color:#C9A96E;letter-spacing:-1px;">
                    {{ strtoupper(substr($card->display_name, 0, 1)) }}
                </div>
            @endif
        </div>
        @if ($card->logo)
        <div style="position:absolute;bottom:1px;right:1px;width:22px;height:22px;border-radius:50%;
                    border:2px solid #0B0B0D;overflow:hidden;background:#1e1e24;">
            <img src="{{ Storage::url($card->logo) }}" alt="" style="width:100%;height:100%;object-fit:contain;">
        </div>
        @endif
    </div>

    {{-- Nome --}}
    <h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:400;
               color:#F2F2F4;letter-spacing:-.3px;line-height:1.2;margin:14px 0 0;text-align:center;">
        {{ $card->display_name }}
    </h1>

    @if ($card->title)
    <p style="font-size:10px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;
              color:#C9A96E;margin-top:8px;text-align:center;">
        {{ $card->title }}{{ $card->company ? ' · ' . $card->company : '' }}
    </p>
    @elseif ($card->company)
    <p style="font-size:10px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;
              color:#C9A96E;margin-top:8px;text-align:center;">
        {{ $card->company }}
    </p>
    @endif

    {{-- Linha dourada ── --}}
    <div style="width:32px;height:1px;background:linear-gradient(90deg,transparent,#C9A96E,transparent);margin:18px 0 0;"></div>
</div>

{{-- ═══ ACTION PILLS ═══ --}}
<div style="display:flex;justify-content:center;gap:8px;padding:18px 20px 0;flex-wrap:wrap;">
    <a href="{{ route('card.vcard', $card->slug) }}" class="dk-action-pill" style="text-decoration:none;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        Salvar contato
    </a>
    <button type="button" onclick="dkShareNative()" class="dk-action-pill">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        Compartilhar
    </button>
    <button type="button" onclick="dkCopyLink()" class="dk-action-pill">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        Copiar link
    </button>
</div>
<div id="dk-copy-fb" style="display:none;text-align:center;font-size:11px;color:#4ade80;font-weight:600;padding:8px 0 0;"></div>

<div style="height:1px;background:var(--dk-border);margin:20px 22px 0;"></div>

{{-- ═══ BIO ═══ --}}
@if ($card->bio)
<div class="dk-section">
    <p class="dk-section-label">Sobre</p>
    <p style="font-size:14.5px;color:#7e7e92;line-height:1.75;white-space:pre-line;">{{ $card->bio }}</p>
</div>
<div class="dk-rule" style="margin-top:24px;"></div>
@endif

{{-- ═══ LINKS CUSTOMIZADOS ═══ --}}
@if ($customLinks->isNotEmpty())
<div class="dk-section">
    <p class="dk-section-label">Links</p>
    @foreach ($customLinks as $link)
    <a href="{{ route('card.link.click', [$card->slug, $link->id]) }}" rel="noopener" class="dk-link">
        <span class="dk-link-dot"></span>
        <span class="dk-link-name">{{ $link->label }}</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @endforeach
</div>
<div class="dk-rule" style="margin-top:24px;"></div>
@endif

{{-- ═══ REDES SOCIAIS ═══ --}}
@if ($socialLinks->isNotEmpty())
<div class="dk-section">
    <p class="dk-section-label">Redes sociais</p>
    <div class="dk-social-track" id="dk-social-track">
        @foreach ($socialLinks as $link)
        <a href="{{ route('card.link.click', [$card->slug, $link->id]) }}" target="_blank" rel="noopener"
           class="dk-social-chip">
            <i data-lucide="{{ $link->lucide_icon }}" style="width:13px;height:13px;color:var(--dk-text-muted);flex-shrink:0;"></i>
            {{ $link->label }}
        </a>
        @endforeach
    </div>
</div>
<div class="dk-rule" style="margin-top:20px;"></div>
<script>
(function () {
    const t = document.getElementById('dk-social-track');
    if (!t) return;
    let drag = false, sx = 0, ss = 0;
    t.addEventListener('mousedown', e => { drag = true; sx = e.pageX; ss = t.scrollLeft; });
    document.addEventListener('mouseup', () => drag = false);
    document.addEventListener('mousemove', e => { if (!drag) return; e.preventDefault(); t.scrollLeft = ss - (e.pageX - sx); });
})();
</script>
@endif

{{-- ═══ SERVIÇOS ═══ --}}
@if ($activeServices->isNotEmpty())
<div class="dk-section">
    <p class="dk-section-label">Serviços</p>
    @foreach ($activeServices as $service)
    <button type="button" class="dk-svc" onclick="pixModal.open({{ $service->id }})">
        <div class="dk-svc-icon">
            <svg data-lucide="{{ $service->lucide_icon }}" width="18" height="18" stroke="#C9A96E" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></svg>
        </div>
        <div class="dk-svc-info">
            <div class="dk-svc-name">{{ $service->name }}</div>
            @if ($service->description)
            <div class="dk-svc-desc">{{ $service->description }}</div>
            @endif
        </div>
        <span class="dk-svc-price">{{ $service->formatted_price }}</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    @endforeach
</div>
<div class="dk-rule" style="margin-top:24px;"></div>
@endif

{{-- ═══ PIX DINÂMICO ═══ --}}
@if ($card->pix_key)
<div class="dk-section">
    <button type="button" onclick="pixDinamicoModal.open()" class="dk-pix-btn">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Pagar via PIX — valor livre
    </button>
    <p style="font-size:10px;color:var(--dk-text-muted);text-align:center;margin-top:8px;letter-spacing:.5px;text-transform:uppercase;">
        Digite o valor · Gera o QR · Paga
    </p>
</div>
<div class="dk-rule" style="margin-top:24px;"></div>
@endif

{{-- ═══ CONTATO ═══ --}}
@if ($card->contact_phone || $card->contact_landline || $card->contact_email || $card->website)
<div class="dk-section">
    <p class="dk-section-label">Contato</p>

    @if ($card->contact_phone)
    <a href="tel:{{ preg_replace('/\D/', '', $card->contact_phone) }}" class="dk-row">
        <div class="dk-row-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C9A96E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
        </div>
        <div class="dk-row-info">
            <div class="dk-row-label">Celular / WhatsApp</div>
            <div class="dk-row-value">{{ $card->contact_phone }}</div>
        </div>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @endif

    @if ($card->contact_landline)
    <a href="tel:{{ preg_replace('/\D/', '', $card->contact_landline) }}" class="dk-row">
        <div class="dk-row-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C9A96E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.12 6.12l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <div class="dk-row-info">
            <div class="dk-row-label">Telefone fixo</div>
            <div class="dk-row-value">{{ $card->contact_landline }}</div>
        </div>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @endif

    @if ($card->contact_email)
    <a href="mailto:{{ $card->contact_email }}" class="dk-row">
        <div class="dk-row-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C9A96E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <div class="dk-row-info">
            <div class="dk-row-label">E-mail</div>
            <div class="dk-row-value">{{ $card->contact_email }}</div>
        </div>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @endif

    @if ($card->website)
    <a href="{{ $card->website }}" target="_blank" rel="noopener" class="dk-row">
        <div class="dk-row-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C9A96E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        </div>
        <div class="dk-row-info">
            <div class="dk-row-label">Website</div>
            <div class="dk-row-value">{{ parse_url($card->website, PHP_URL_HOST) ?? $card->website }}</div>
        </div>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @endif
</div>
<div class="dk-rule" style="margin-top:24px;"></div>
@endif

{{-- ═══ LOCALIZAÇÃO ═══ --}}
@if ($card->address)
@php $mapsUrl = 'https://maps.google.com/?q=' . urlencode($card->address); @endphp
<div class="dk-section">
    <p class="dk-section-label">Localização</p>
    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="dk-map-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C9A96E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <span style="font-size:13px;color:#7a7a8a;line-height:1.45;flex:1;">{{ $card->address }}</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dk-arrow" style="margin-top:2px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
    </a>
    <div class="dk-map-actions">
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="dk-map-action-btn" style="text-decoration:none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
            Google Maps
        </a>
        <button type="button" onclick="dkShareLocation()" class="dk-map-action-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            Compartilhar
        </button>
    </div>
    <div id="dk-loc-fb" style="display:none;margin-top:8px;text-align:center;font-size:12px;color:#4ade80;font-weight:600;">✓ Link copiado!</div>
</div>
<div class="dk-rule" style="margin-top:24px;"></div>
<script>
function dkShareLocation() {
    const url = '{{ $mapsUrl }}';
    if (navigator.share) {
        navigator.share({ title: '{{ addslashes($card->display_name) }} — Localização', url }).catch(()=>{});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const el = document.getElementById('dk-loc-fb');
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        });
    }
}
</script>
@endif

{{-- ═══ GALERIA ═══ --}}
@if ($card->photos->isNotEmpty())
@php $photos = $card->photos; @endphp
<div class="dk-section" x-data="{ open: true }">
    <button type="button" class="dk-accordion-btn" @click="open = !open">
        <p class="dk-section-label" style="margin-bottom:0;">
            Galeria
            <span style="color:var(--dk-surf3);font-size:9px;"> ({{ $photos->count() }})</span>
        </p>
        <i data-lucide="chevron-down" class="dk-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="dk-gallery-grid" style="margin-bottom:8px;">
            @foreach ($photos as $i => $photo)
            <button type="button" onclick="dkGallery.open({{ $i }})" class="dk-gallery-cell">
                <img src="{{ $photo->thumbnail_url ?? Storage::url($photo->path) }}"
                     alt="{{ $photo->caption ?? 'Foto ' . ($i+1) }}"
                     loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .2s;"
                     onmouseenter="this.style.transform='scale(1.05)'" onmouseleave="this.style.transform=''">
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- Lightbox dark ── --}}
<div id="dk-lightbox"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.97);
            flex-direction:column;align-items:center;justify-content:center;"
     onclick="if(event.target===this)dkGallery.close()">
    <div style="position:absolute;top:0;left:0;right:0;display:flex;align-items:center;justify-content:space-between;
                padding:14px 16px;z-index:2;">
        <span id="dk-lb-counter" style="font-size:12px;color:rgba(255,255,255,.4);font-weight:500;"></span>
        <button onclick="dkGallery.close()"
                style="background:rgba(255,255,255,.08);border:none;color:#fff;width:44px;height:44px;
                       border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:20px;">×</button>
    </div>
    <div style="width:100%;max-height:80vh;display:flex;align-items:center;justify-content:center;padding:60px 48px;">
        <img id="dk-lb-img" src="" alt=""
             style="max-width:100%;max-height:76vh;object-fit:contain;border-radius:6px;
                    transition:opacity .18s;box-shadow:0 8px 48px rgba(0,0,0,.8);">
    </div>
    <div id="dk-lb-caption" style="font-size:13px;color:rgba(255,255,255,.5);text-align:center;padding:0 24px 12px;min-height:20px;"></div>
    <button onclick="dkGallery.prev()"
            style="position:absolute;left:8px;top:50%;transform:translateY(-50%);
                   background:rgba(255,255,255,.08);border:none;color:#fff;
                   width:48px;height:48px;border-radius:50%;display:flex;align-items:center;
                   justify-content:center;cursor:pointer;font-size:22px;">‹</button>
    <button onclick="dkGallery.next()"
            style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                   background:rgba(255,255,255,.08);border:none;color:#fff;
                   width:48px;height:48px;border-radius:50%;display:flex;align-items:center;
                   justify-content:center;cursor:pointer;font-size:22px;">›</button>
    <div id="dk-lb-dots" style="display:flex;gap:6px;padding-bottom:20px;"></div>
</div>
<script>
(function () {
    @php
    $photosJson = $photos->map(fn($p) => [
        'src'     => Storage::url($p->path),
        'caption' => $p->caption ?? '',
    ]);
    @endphp
    const photos = @json($photosJson);
    const lb = document.getElementById('dk-lightbox');
    const lbImg = document.getElementById('dk-lb-img');
    const lbCap = document.getElementById('dk-lb-caption');
    const lbCount = document.getElementById('dk-lb-counter');
    const lbDots = document.getElementById('dk-lb-dots');
    let current = 0;

    function buildDots() {
        lbDots.innerHTML = '';
        photos.forEach((_, i) => {
            const d = document.createElement('div');
            d.style.cssText = 'width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.2);cursor:pointer;transition:all .15s';
            d.onclick = () => dkGallery.go(i);
            lbDots.appendChild(d);
        });
    }
    function updateDots() {
        [...lbDots.children].forEach((d, i) => {
            d.style.background = i === current ? '#C9A96E' : 'rgba(255,255,255,.2)';
            d.style.width = i === current ? '18px' : '6px';
            d.style.borderRadius = '99px';
        });
    }

    window.dkGallery = {
        open(idx) {
            current = idx; lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            buildDots(); this.go(idx);
        },
        close() { lb.style.display = 'none'; document.body.style.overflow = ''; },
        go(idx) {
            if (idx < 0) idx = photos.length - 1;
            if (idx >= photos.length) idx = 0;
            current = idx;
            lbImg.style.opacity = '0';
            setTimeout(() => {
                lbImg.src = photos[idx].src;
                lbImg.alt = photos[idx].caption || 'Foto ' + (idx+1);
                lbCap.textContent = photos[idx].caption;
                lbCount.textContent = (idx+1) + ' / ' + photos.length;
                lbImg.style.opacity = '1';
                updateDots();
            }, 80);
        },
        prev() { this.go(current - 1); },
        next() { this.go(current + 1); },
    };

    document.addEventListener('keydown', e => {
        if (lb.style.display === 'none') return;
        if (e.key === 'ArrowRight') dkGallery.next();
        if (e.key === 'ArrowLeft')  dkGallery.prev();
        if (e.key === 'Escape')     dkGallery.close();
    });
    let touchX = null;
    lb.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', e => {
        if (touchX === null) return;
        const diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) diff > 0 ? dkGallery.next() : dkGallery.prev();
        touchX = null;
    }, { passive: true });
})();
</script>
<div class="dk-rule" style="margin-top:4px;"></div>
@endif

{{-- ═══ AGENDAR ═══ --}}
@if ($card->schedule && $card->schedule->is_active)
<div class="dk-section" x-data="{ open: false }">
    <button type="button" class="dk-accordion-btn" @click="open = !open">
        <p class="dk-section-label" style="margin-bottom:0;">Agendar horário</p>
        <i data-lucide="chevron-down" class="dk-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="padding-bottom:12px;">
        <livewire:schedule.appointment-calendar :card="$card" />
    </div>
</div>
<div class="dk-rule" style="margin-top:4px;"></div>
@endif

{{-- ═══ FORMULÁRIO ═══ --}}
<div class="dk-section" x-data="{ open: false }">
    <button type="button" class="dk-accordion-btn" @click="open = !open">
        <p class="dk-section-label" style="margin-bottom:0;">Enviar mensagem</p>
        <i data-lucide="chevron-down" class="dk-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="padding-bottom:12px;">
        <div id="dk-form-wrap">
            <livewire:card.contact-form :card="$card" />
        </div>
    </div>
</div>
<div class="dk-rule" style="margin-top:4px;"></div>

{{-- ═══ COMPARTILHAR ═══ --}}
<div class="dk-section" x-data="{ open: false }">
    <button type="button" class="dk-accordion-btn" @click="open = !open">
        <p class="dk-section-label" style="margin-bottom:0;">Compartilhar perfil</p>
        <i data-lucide="chevron-down" class="dk-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="padding-bottom:12px;">

        <div style="display:flex;justify-content:center;padding:10px 0 14px;">
            <div style="background:#fff;padding:10px;border-radius:12px;">
                {!! $qrSvg !!}
            </div>
        </div>

        <div class="dk-share-link-box">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--dk-text-muted);flex-shrink:0;"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <span style="font-size:12px;color:var(--dk-text-muted);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                nexosn.pageup.net.br/u/<strong style="color:#8a8a9a;">{{ $card->slug }}</strong>
            </span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <button type="button" onclick="dkCopyLink()"  class="dk-share-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--dk-text-muted);"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                Copiar link
            </button>
            <button type="button" onclick="dkShareNative()" class="dk-share-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--dk-text-muted);"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Compartilhar
            </button>
            <button type="button" onclick="dkDownloadQr()" class="dk-share-btn" style="grid-column:1/-1;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--dk-text-muted);"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Baixar QR Code (PNG)
            </button>
        </div>
        <div id="dk-share-fb" style="display:none;text-align:center;font-size:12px;color:#4ade80;font-weight:600;margin-top:8px;"></div>
    </div>
</div>

{{-- ═══ MODAIS PIX ═══ --}}
{{-- Modal PIX por serviço --}}
<div id="pix-modal-overlay" class="pix-modal-overlay" onclick="pixModal.close()"></div>
<div id="pix-modal-sheet" class="pix-modal-sheet" role="dialog" aria-modal="true">
    <div class="pix-modal-handle"></div>
    <div id="pix-modal-body"><div class="pix-spinner"></div></div>
</div>

{{-- Modal PIX dinâmico --}}
@if ($card->pix_key)
<div id="pix-din-overlay" class="pix-modal-overlay" onclick="pixDinamicoModal.close()"></div>
<div id="pix-din-sheet" class="pix-modal-sheet" role="dialog" aria-modal="true">
    <div class="pix-modal-handle"></div>
    <div id="pix-din-body">
        <p class="pix-modal-title">Pagar via PIX</p>
        <p style="font-size:12px;color:var(--dk-text-muted);text-align:center;margin-bottom:16px;">
            Digite o valor que deseja pagar
        </p>
        <div style="position:relative;margin-bottom:16px;">
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:15px;font-weight:700;color:#9a9aaa;">R$</span>
            <input type="number" id="pix-din-valor" min="0.01" max="9999.99" step="0.01"
                   placeholder="0,00"
                   style="width:100%;padding:14px 14px 14px 42px;border:2px solid var(--dk-border-md);
                          border-radius:14px;font-size:20px;font-weight:700;color:#F0F0F4;
                          background:var(--dk-surf2);outline:none;box-sizing:border-box;-moz-appearance:textfield;"
                   oninput="this.style.borderColor='var(--dk-border-md)';document.getElementById('pix-din-erro').textContent=''"
                   onkeydown="if(event.key==='Enter') pixDinamicoModal.gerar()">
        </div>
        <p id="pix-din-erro" style="color:#f87171;font-size:12px;text-align:center;min-height:16px;margin-bottom:8px;"></p>
        <button onclick="pixDinamicoModal.gerar()"
                style="width:100%;padding:14px;border:none;border-radius:14px;font-size:15px;font-weight:700;
                       color:#1a0e00;cursor:pointer;background:linear-gradient(100deg,#7A5520,#C9A96E 55%,#E8C98A);">
            Gerar QR PIX
        </button>
        <button onclick="pixDinamicoModal.close()"
                style="display:block;width:100%;margin-top:10px;padding:12px;border:none;
                       background:var(--dk-surf2);border-radius:14px;font-size:13px;font-weight:600;
                       color:var(--dk-text-muted);cursor:pointer;">
            Fechar
        </button>
    </div>
</div>
@endif

{{-- ═══ vCard data ═══ --}}
<script id="vcard-data" type="application/json">
{
    "fn":    "{{ addslashes($card->display_name) }}",
    "title": "{{ addslashes($card->title ?? '') }}",
    "org":   "{{ addslashes($card->company ?? '') }}",
    "tel":   "{{ preg_replace('/\D/', '', $card->contact_phone ?? '') }}",
    "email": "{{ addslashes($card->contact_email ?? '') }}",
    "url":   "{{ url('/u/' . $card->slug) }}",
    "note":  "{{ addslashes(strip_tags($card->bio ?? '')) }}",
    "slug":  "{{ $card->slug }}"
}
</script>

{{-- ═══ RODAPÉ ═══ --}}
<div class="dk-footer" style="margin-top:24px;">
    @if ($card->show_watermark)
    <p class="dk-footer-brand" style="margin-bottom:10px;">
        Criado com <span style="color:#3a3a48;font-weight:800;">NEXOSN</span>
    </p>
    @endif
    <a href="https://nexosn.pageup.net.br" target="_blank" rel="noopener"
       style="display:inline-flex;align-items:center;gap:5px;text-decoration:none;opacity:.3;transition:opacity .2s;"
       onmouseenter="this.style.opacity='.7'" onmouseleave="this.style.opacity='.3'">
        <svg viewBox="0 0 16 16" fill="none" style="width:11px;height:11px;flex-shrink:0;">
            <line x1="3" y1="3"  x2="3"  y2="13" stroke="#C9A96E" stroke-width="1.75" stroke-linecap="round"/>
            <line x1="3" y1="3"  x2="13" y2="13" stroke="#C9A96E" stroke-width="1.75" stroke-linecap="round"/>
            <line x1="13" y1="3" x2="13" y2="13" stroke="#C9A96E" stroke-width="1.75" stroke-linecap="round"/>
            <circle cx="3"  cy="3"  r="2" fill="#C9A96E"/>
            <circle cx="3"  cy="13" r="2" fill="#C9A96E"/>
            <circle cx="13" cy="3"  r="2" fill="#C9A96E"/>
            <circle cx="13" cy="13" r="2" fill="#C9A96E"/>
        </svg>
        <span style="font-family:'Inter',sans-serif;font-size:11px;font-weight:700;color:#C9A96E;letter-spacing:.02em;line-height:1;">
            NEX<span style="opacity:.4;">OSN</span>
        </span>
    </a>
</div>

{{-- ═══ COOKIE BANNER DARK ═══ --}}
<div id="cookie-banner-dk"
     style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9990;
            background:#0d0d10;padding:14px 16px 20px;
            box-shadow:0 -4px 24px rgba(0,0,0,.7);
            border-top:1px solid rgba(255,255,255,0.08);">
    <p style="font-size:12px;color:rgba(255,255,255,.6);line-height:1.55;margin:0 0 12px;">
        {!! \App\Models\AppSetting::get('cookie_banner_text', 'Usamos cookies essenciais. Ao continuar, você concorda com nossa <a href="/legal/cookies" style="color:#C9A96E;">Política de Cookies</a>.') !!}
    </p>
    <div style="display:flex;gap:8px;">
        <button onclick="dkCookieConsent('all')"
                style="flex:1;padding:10px;border-radius:10px;border:none;cursor:pointer;
                       font-size:12px;font-weight:700;background:linear-gradient(100deg,#7A5520,#C9A96E);color:#1a0e00;">
            Aceitar todos
        </button>
        <button onclick="dkCookieConsent('essential')"
                style="flex:1;padding:10px;border-radius:10px;border:none;cursor:pointer;
                       font-size:12px;font-weight:600;background:rgba(255,255,255,.06);color:rgba(255,255,255,.7);">
            Apenas essenciais
        </button>
    </div>
</div>

{{-- ═══ SCRIPTS ═══ --}}
<script>
const _dkUrl  = '{{ url('/u/' . $card->slug) }}';
const _dkName = '{{ addslashes($card->display_name) }}';
const _dkSlug = '{{ $card->slug }}';

function dkCopyLink() {
    navigator.clipboard.writeText(_dkUrl).then(() => {
        const fb = document.getElementById('dk-copy-fb');
        const sf = document.getElementById('dk-share-fb');
        if (fb) { fb.textContent = '✓ Link copiado!'; fb.style.display = 'block'; setTimeout(() => fb.style.display = 'none', 3000); }
        if (sf) { sf.textContent = '✓ Link copiado!'; sf.style.display = 'block'; setTimeout(() => sf.style.display = 'none', 3000); }
    });
}
function dkShareNative() {
    if (navigator.share) {
        navigator.share({ title: _dkName, url: _dkUrl }).catch(() => dkCopyLink());
    } else {
        dkCopyLink();
    }
}
function dkDownloadQr() {
    // QR SVG está dentro do div de compartilhar (background:#fff;padding:10px)
    const qrEl = document.querySelector('.dk-section [style*="background:#fff"] svg');
    if (!qrEl) return;
    const svgData = new XMLSerializer().serializeToString(qrEl);
    const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(svgBlob);
    const img = new Image();
    img.onload = () => {
        const canvas = document.createElement('canvas');
        canvas.width = canvas.height = 600;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, 600, 600);
        ctx.drawImage(img, 0, 0, 600, 600);
        URL.revokeObjectURL(url);
        const a = document.createElement('a');
        a.href = canvas.toDataURL('image/png');
        a.download = 'qr-' + _dkSlug + '.png';
        a.click();
    };
    img.src = url;
}
function dkCookieConsent(choice) {
    localStorage.setItem('cookie_consent', choice);
    document.getElementById('cookie-banner-dk').style.display = 'none';
}
(function () {
    if (!localStorage.getItem('cookie_consent')) {
        document.getElementById('cookie-banner-dk').style.display = 'block';
    }
})();

// ── Modal PIX por serviço ──
(function () {
    const slug    = _dkSlug;
    const overlay = document.getElementById('pix-modal-overlay');
    const sheet   = document.getElementById('pix-modal-sheet');
    const body    = document.getElementById('pix-modal-body');
    @if (!empty($autoOpenService))
    let _autoOpen = {{ $autoOpenService }};
    @else
    let _autoOpen = null;
    @endif

    window.pixModal = {
        open(serviceId) {
            body.innerHTML = '<div class="pix-spinner"></div>';
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => sheet.classList.add('open'));
            fetch(`/u/${slug}/servico/${serviceId}/payload`)
                .then(r => r.json())
                .then(data => {
                    body.innerHTML = `
                        <p class="pix-modal-title">${data.name}</p>
                        <p class="pix-modal-price">${data.formatted}</p>
                        <div class="pix-qr-wrap">${data.qr_svg}</div>
                        <p style="font-size:11px;color:var(--dk-text-muted);text-align:center;margin-bottom:12px;">
                            Abra o app do seu banco e escaneie o QR ou copie o código abaixo.
                        </p>
                        <div class="pix-payload-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4a4a58" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span class="pix-payload-text">${data.payload}</span>
                        </div>
                        <button class="pix-copy-btn" onclick="pixModal.copy('${data.payload}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a0e00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Pix copia e cola
                        </button>
                        <p class="pix-feedback" id="pix-fb"></p>
                        <button onclick="pixModal.close()" style="display:block;width:100%;margin-top:10px;padding:12px;
                                border:none;background:var(--dk-surf2);border-radius:14px;font-size:13px;font-weight:600;
                                color:var(--dk-text-muted);cursor:pointer;">Fechar</button>
                    `;
                })
                .catch(() => { body.innerHTML = '<p style="text-align:center;font-size:13px;color:#f87171;padding:24px 0;">Erro ao carregar. Tente novamente.</p>'; });
        },
        copy(payload) {
            navigator.clipboard.writeText(payload).then(() => {
                const fb = document.getElementById('pix-fb');
                if (fb) fb.textContent = '✓ Código copiado! Cole no seu banco.';
            });
        },
        close() {
            sheet.classList.remove('open');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        },
    };
    if (_autoOpen) window.addEventListener('load', () => pixModal.open(_autoOpen));

    let touchY = null;
    sheet.addEventListener('touchstart', e => { touchY = e.touches[0].clientY; }, { passive: true });
    sheet.addEventListener('touchend', e => {
        if (touchY !== null && (e.changedTouches[0].clientY - touchY) > 60) pixModal.close();
        touchY = null;
    }, { passive: true });
})();

// ── Modal PIX dinâmico ──
@if ($card->pix_key)
(function () {
    const slug    = _dkSlug;
    const overlay = document.getElementById('pix-din-overlay');
    const sheet   = document.getElementById('pix-din-sheet');
    const body    = document.getElementById('pix-din-body');

    window.pixDinamicoModal = {
        open() {
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => sheet.classList.add('open'));
            setTimeout(() => { const v = document.getElementById('pix-din-valor'); if(v) v.focus(); }, 350);
        },
        close() {
            sheet.classList.remove('open');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
            setTimeout(() => this.reset(), 300);
        },
        reset() {
            body.innerHTML = `
                <p class="pix-modal-title">Pagar via PIX</p>
                <p style="font-size:12px;color:var(--dk-text-muted);text-align:center;margin-bottom:16px;">Digite o valor que deseja pagar</p>
                <div style="position:relative;margin-bottom:16px;">
                    <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:15px;font-weight:700;color:#9a9aaa;">R$</span>
                    <input type="number" id="pix-din-valor" min="0.01" max="9999.99" step="0.01"
                           placeholder="0,00"
                           style="width:100%;padding:14px 14px 14px 42px;border:2px solid var(--dk-border-md);
                                  border-radius:14px;font-size:20px;font-weight:700;color:#F0F0F4;
                                  background:var(--dk-surf2);outline:none;box-sizing:border-box;-moz-appearance:textfield;"
                           oninput="this.style.borderColor='var(--dk-border-md)';document.getElementById('pix-din-erro').textContent=''"
                           onkeydown="if(event.key==='Enter') pixDinamicoModal.gerar()">
                </div>
                <p id="pix-din-erro" style="color:#f87171;font-size:12px;text-align:center;min-height:16px;margin-bottom:8px;"></p>
                <button onclick="pixDinamicoModal.gerar()"
                        style="width:100%;padding:14px;border:none;border-radius:14px;font-size:15px;font-weight:700;
                               color:#1a0e00;cursor:pointer;background:linear-gradient(100deg,#7A5520,#C9A96E 55%,#E8C98A);">
                    Gerar QR PIX
                </button>
                <button onclick="pixDinamicoModal.close()"
                        style="display:block;width:100%;margin-top:10px;padding:12px;border:none;
                               background:var(--dk-surf2);border-radius:14px;font-size:13px;font-weight:600;
                               color:var(--dk-text-muted);cursor:pointer;">Fechar</button>
            `;
        },
        gerar() {
            const input = document.getElementById('pix-din-valor');
            const erro  = document.getElementById('pix-din-erro');
            const val   = parseFloat(input?.value);
            if (!val || val < 0.01) { if(input) input.style.borderColor='#ef4444'; if(erro) erro.textContent='Digite um valor válido (mínimo R$ 0,01).'; return; }
            if (val > 9999.99) { if(input) input.style.borderColor='#ef4444'; if(erro) erro.textContent='Valor máximo: R$ 9.999,99.'; return; }
            body.innerHTML = '<div class="pix-spinner"></div>';
            fetch(`/u/${slug}/pix/payload?amount=${val.toFixed(2)}`)
                .then(r => r.json())
                .then(data => {
                    body.innerHTML = `
                        <p class="pix-modal-title">PIX — ${data.formatted}</p>
                        <div class="pix-qr-wrap">${data.qr_svg}</div>
                        <p style="font-size:11px;color:var(--dk-text-muted);text-align:center;margin-bottom:12px;">
                            Abra o app do seu banco e escaneie o QR ou copie o código abaixo.
                        </p>
                        <div class="pix-payload-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4a4a58" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span class="pix-payload-text">${data.payload}</span>
                        </div>
                        <button class="pix-copy-btn" onclick="navigator.clipboard.writeText('${data.payload}').then(()=>{document.getElementById('pix-din-fb').textContent='✓ Código copiado! Cole no seu banco.'})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a0e00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Pix copia e cola
                        </button>
                        <p class="pix-feedback" id="pix-din-fb"></p>
                        <button onclick="pixDinamicoModal.close()" style="display:block;width:100%;margin-top:10px;padding:12px;
                                border:none;background:var(--dk-surf2);border-radius:14px;font-size:13px;font-weight:600;
                                color:var(--dk-text-muted);cursor:pointer;">Fechar</button>
                    `;
                })
                .catch(() => { body.innerHTML = '<p style="text-align:center;font-size:13px;color:#f87171;padding:24px 0;">Erro ao gerar PIX. Tente novamente.</p>'; });
        },
    };

    let touchY = null;
    sheet.addEventListener('touchstart', e => { touchY = e.touches[0].clientY; }, { passive: true });
    sheet.addEventListener('touchend', e => {
        if (touchY !== null && (e.changedTouches[0].clientY - touchY) > 60) pixDinamicoModal.close();
        touchY = null;
    }, { passive: true });
})();
@endif
</script>

@endsection
