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
        --card-primary: {{ $card->primary_color }};
        --card-button:  {{ $card->button_color }};
        --ui-icon: #b0b7c3;
        --ui-label: #9ca3af;
        --ui-text: #374151;
        --ui-heading: #111827;
        --ui-border: rgba(0,0,0,0.07);
        --ui-bg: #f7f7f6;
        --ui-section-bg: #ffffff;
        --ui-tap: rgba(0,0,0,0.03);
    }

    [x-cloak] { display: none !important; }

    /* ── Wrapper de seções ── */
    .sections-wrap {
        padding: 2px 14px 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* ── Card de seção ── */
    .cs {
        background: var(--ui-section-bg);
        border-radius: 16px;
        border: 1px solid var(--ui-border);
        overflow: hidden;
    }

    /* ── Header da seção ── */
    .cs-head {
        width: 100%; background: none; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between;
        padding: 13px 16px; gap: 10px; text-align: left;
        -webkit-tap-highlight-color: transparent;
    }
    .cs-head:active { background: var(--ui-tap); }

    .cs-label {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 600; color: var(--ui-label);
        text-transform: uppercase; letter-spacing: .6px;
    }

    .cs-chevron {
        width: 15px; height: 15px; flex-shrink: 0;
        color: #d1d5db; transition: transform .22s ease;
    }

    /* ── Corpo da seção ── */
    .cs-body { padding: 0 16px 14px; }
    .cs-body-flush { padding: 0; }

    /* ── Linhas de contato ── */
    .crow {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 0;
        min-height: 44px;
        border-bottom: 1px solid var(--ui-border);
        text-decoration: none; color: var(--ui-text);
        font-size: 14px; line-height: 1.4;
        transition: color .12s;
    }
    .crow:last-child { border-bottom: none; padding-bottom: 0; }
    .crow:active { color: var(--ui-heading); }

    /* ── Botões de ação ── */
    .abtn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 13px 16px; border-radius: 12px;
        font-size: 14px; font-weight: 600; cursor: pointer;
        text-decoration: none; border: none;
        transition: opacity .15s, transform .12s;
        -webkit-tap-highlight-color: transparent;
    }
    .abtn:active { transform: scale(.98); opacity: .85; }
    .abtn-primary { background-color: var(--card-button); color: #fff; }
    .abtn-ghost {
        background: var(--ui-section-bg);
        color: var(--ui-text);
        border: 1px solid var(--ui-border);
    }
    .abtn-pix {
        background: linear-gradient(100deg, #F77F00 0%, #FCBF49 100%);
        color: #431900; font-weight: 700; letter-spacing: .1px;
    }

    /* ── Social scroll (novo) ── */
    .social-track {
        display: flex;
        gap: 7px;
        overflow-x: auto;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
        cursor: grab;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    .social-track:active { cursor: grabbing; }
    .social-track::-webkit-scrollbar { display: none; }

    .spill {
        flex-shrink: 0;
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 13px 10px 11px;
        min-height: 44px;
        border-radius: 12px;
        text-decoration: none;
        transition: opacity .12s, transform .1s;
        white-space: nowrap;
        -webkit-tap-highlight-color: transparent;
    }
    .spill:active { transform: scale(.92); opacity: .8; }
    .spill-label { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.92); }

    /* cores por plataforma */
    .spill-ig { background: #833AB4; }
    .spill-wa { background: #1a9e47; }
    .spill-li { background: #0A66C2; }
    .spill-yt { background: #c62828; }
    .spill-tk { background: #111111; }
    .spill-tw { background: #1a1a1a; }
    .spill-fb { background: #1877F2; }
    .spill-tg { background: #2AABEE; }
    .spill-pi { background: #E60023; }
    .spill-sp { background: #1DB954; }
    .spill-def { background-color: var(--card-primary); }

    /* dots de scroll */
    .scroll-dots { display: flex; justify-content: center; gap: 4px; padding: 8px 0 2px; }
    .sd { width: 4px; height: 4px; border-radius: 50%; background: #d1d5db; transition: all .22s; }
    .sd.on { width: 14px; border-radius: 3px; background: var(--card-primary); }

    /* barra de ação — ícones circulares + label */
    .action-bar {
        display: flex; justify-content: center; gap: 28px;
        padding: 14px 16px 16px;
        background: #fff;
    }
    .action-icon-btn {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        background: none; border: none; cursor: pointer; padding: 0;
        -webkit-tap-highlight-color: transparent;
        transition: transform .12s, opacity .12s;
        text-decoration: none; min-width: 56px;
    }
    .action-icon-btn:active { transform: scale(.92); opacity: .75; }
    .action-icon-circle {
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .action-icon-circle-primary {
        background-color: var(--card-button);
    }
    .action-icon-circle-ghost {
        background: #f0f0ef;
        border: 1px solid rgba(0,0,0,0.07);
    }
    .action-icon-label {
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-align: center; line-height: 1.3; white-space: nowrap;
    }

    /* links customizados — compactos, outline */
    .clink-wrap {
        padding: 6px 14px 2px;
        display: flex; flex-direction: column; gap: 6px;
    }
    .clink {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 16px; border-radius: 12px; min-height: 44px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none; border: 1.5px solid rgba(0,0,0,0.09);
        background: #fff; color: var(--ui-heading);
        transition: background .12s, transform .12s;
        -webkit-tap-highlight-color: transparent;
    }
    .clink:active { transform: scale(.98); background: #f8f8f7; }
    .clink-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background-color: var(--card-button);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .clink span { flex: 1; }

    /* ── Serviços (M-11) ── */
    .svc-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 0; min-height: 56px;
        border-bottom: 1px solid var(--ui-border);
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }
    .svc-item:last-child { border-bottom: none; }
    .svc-item:active { opacity: .75; }
    .svc-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background-color: var(--card-button);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .svc-info { flex: 1; min-width: 0; }
    .svc-name { font-size: 13.5px; font-weight: 600; color: var(--ui-heading); line-height: 1.3; }
    .svc-desc { font-size: 12px; color: var(--ui-label); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .svc-price {
        font-size: 14px; font-weight: 700; color: var(--card-button);
        white-space: nowrap; flex-shrink: 0;
    }

    /* ── Modal PIX ── */
    .pix-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 9998;
        background: rgba(0,0,0,0.55); backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
    }
    .pix-modal-sheet {
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 9999;
        background: #fff; border-radius: 24px 24px 0 0;
        padding: 20px 20px 36px; max-width: 480px; margin: 0 auto;
        transform: translateY(100%);
        transition: transform .32s cubic-bezier(.32,1,.24,1);
        max-height: 92vh; overflow-y: auto;
    }
    .pix-modal-sheet.open { transform: translateY(0); }
    .pix-modal-handle {
        width: 36px; height: 4px; border-radius: 99px; background: #e5e7eb;
        margin: 0 auto 18px;
    }
    .pix-modal-title { font-size: 16px; font-weight: 700; color: var(--ui-heading); text-align: center; margin-bottom: 4px; }
    .pix-modal-price {
        font-size: 28px; font-weight: 800; color: var(--card-button);
        text-align: center; letter-spacing: -.5px; margin: 6px 0 18px;
    }
    .pix-qr-wrap { display: flex; justify-content: center; margin-bottom: 16px; }
    .pix-qr-wrap svg { max-width: 220px; border-radius: 12px; }
    .pix-payload-box {
        background: var(--ui-bg); border: 1px solid var(--ui-border); border-radius: 12px;
        padding: 11px 14px; display: flex; align-items: center; gap: 8px; margin-bottom: 14px;
    }
    .pix-payload-text {
        flex: 1; font-size: 11px; color: #6b7280; word-break: break-all;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .pix-copy-btn {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 14px; border-radius: 14px;
        background: linear-gradient(100deg, #F77F00 0%, #FCBF49 100%);
        color: #431900; font-size: 14px; font-weight: 700;
        border: none; cursor: pointer; transition: opacity .15s;
    }
    .pix-copy-btn:active { opacity: .85; }
    .pix-feedback {
        text-align: center; font-size: 12px; color: #16a34a;
        font-weight: 600; margin-top: 8px; min-height: 18px;
    }
    .pix-spinner {
        width: 36px; height: 36px; border: 3px solid rgba(0,0,0,0.1);
        border-top-color: var(--card-button); border-radius: 50%;
        animation: pix-spin .7s linear infinite; margin: 40px auto;
    }
    @keyframes pix-spin { to { transform: rotate(360deg); } }
</style>
@endsection

@section('content')

{{-- ═══════════════════ HEADER / CAPA ═══════════════════ --}}
@php
    $socialTypes = ['instagram.com','wa.me','whatsapp','linkedin.com','tiktok.com','youtube.com','twitter.com','x.com','facebook.com','t.me','telegram','pinterest.com','spotify.com'];
    $socialLinks = $card->links->filter(fn ($l) => $l->is_active && collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));
    $customLinks = $card->links->filter(fn ($l) => $l->is_active && !collect($socialTypes)->contains(fn ($d) => str_contains(strtolower($l->url), $d)));

    $socialPillClass = function(string $url): string {
        return match(true) {
            str_contains($url, 'instagram')  => 'spill-ig',
            str_contains($url, 'wa.me') || str_contains($url, 'whatsapp') => 'spill-wa',
            str_contains($url, 'linkedin')   => 'spill-li',
            str_contains($url, 'youtube')    => 'spill-yt',
            str_contains($url, 'tiktok')     => 'spill-tk',
            str_contains($url, 'twitter') || str_contains($url, 'x.com') => 'spill-tw',
            str_contains($url, 'facebook')   => 'spill-fb',
            str_contains($url, 't.me') || str_contains($url, 'telegram') => 'spill-tg',
            str_contains($url, 'pinterest')  => 'spill-pi',
            str_contains($url, 'spotify')    => 'spill-sp',
            default => 'spill-def',
        };
    };
@endphp

<header>
    {{-- Capa --}}
    <div style="height:148px;width:100%;overflow:hidden;position:relative;background-color:var(--card-primary);">
        @if ($card->cover_photo)
            <img src="{{ Storage::url($card->cover_photo) }}"
                 alt="Capa de {{ $card->display_name }}"
                 style="width:100%;height:100%;object-fit:cover;object-position:center center;display:block;">
        @endif
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.03) 0%,rgba(0,0,0,0.22) 100%);"></div>

    </div>

    {{-- Identidade — avatar esquerda + nome direita --}}
    <div style="background:#fff;padding:0 16px 14px;">
        <div style="display:flex;align-items:flex-end;gap:13px;">

            {{-- Avatar ancorado à esquerda, sobrepõe a capa --}}
            <div style="margin-top:-42px;flex-shrink:0;position:relative;">
                <div style="width:88px;height:88px;border-radius:9999px;overflow:hidden;
                            border:3.5px solid #fff;background-color:var(--card-primary);">
                    @if ($card->profile_photo)
                        <img src="{{ Storage::url($card->profile_photo) }}"
                             alt="{{ $card->display_name }}"
                             style="width:100%;height:100%;object-fit:cover;object-position:top center;display:block;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;
                                    justify-content:center;color:#fff;font-size:32px;font-weight:800;letter-spacing:-1px;">
                            {{ strtoupper(substr($card->display_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                {{-- Badge logo --}}
                @if ($card->logo)
                <div style="position:absolute;bottom:1px;right:1px;width:20px;height:20px;border-radius:50%;
                            border:2px solid #fff;overflow:hidden;background:#fff;">
                    <img src="{{ Storage::url($card->logo) }}" alt="" style="width:100%;height:100%;object-fit:contain;">
                </div>
                @endif
            </div>

            {{-- Nome, cargo, empresa --}}
            <div style="flex:1;min-width:0;padding-top:10px;padding-bottom:2px;">
                <h1 style="font-size:18px;font-weight:700;color:#0d1117;letter-spacing:-.4px;
                           line-height:1.2;margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $card->display_name }}
                </h1>
                @if ($card->title)
                <p style="font-size:10px;font-weight:600;color:#9ca3af;letter-spacing:.75px;
                          text-transform:uppercase;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $card->title }}
                </p>
                @endif
                @if ($card->company)
                <p style="font-size:11.5px;font-weight:500;color:var(--card-primary);margin:2px 0 0;
                          opacity:.8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $card->company }}
                </p>
                @endif
            </div>

        </div>
    </div>

    {{-- Barra de ação — ícones circulares + label --}}
    <div class="action-bar">
        <button type="button" onclick="nexosnDownloadVCard()" class="action-icon-btn">
            <div class="action-icon-circle action-icon-circle-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
            </div>
            <span class="action-icon-label">Salvar<br>contato</span>
        </button>
        <button type="button" onclick="nexosnShareNative()" class="action-icon-btn">
            <div class="action-icon-circle action-icon-circle-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </div>
            <span class="action-icon-label">Compartilhar</span>
        </button>
        <button type="button" onclick="nexosnCopyLink()" class="action-icon-btn">
            <div class="action-icon-circle action-icon-circle-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </div>
            <span class="action-icon-label">Copiar link</span>
        </button>
    </div>

    <div style="height:1px;background:rgba(0,0,0,0.06);margin:0 14px;"></div>

</header>

{{-- ═══════════════════ LINKS CUSTOMIZADOS ═══════════════════ --}}
@if ($customLinks->isNotEmpty())
<div class="clink-wrap">
    @foreach ($customLinks as $link)
        <a href="{{ route('card.link.click', [$card->slug, $link->id]) }}" rel="noopener" class="clink">
            <div class="clink-icon">
                <svg data-lucide="{{ $link->lucide_icon }}" width="15" height="15" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <span>{{ $link->label }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    @endforeach
</div>
@endif

{{-- ═══════════════════ REDES SOCIAIS ═══════════════════ --}}
@if ($socialLinks->isNotEmpty())
<div style="background:#fff;padding:10px 14px 10px;margin-top:4px;">
    <p style="font-size:9.5px;font-weight:600;color:#c4c9d4;letter-spacing:.9px;text-transform:uppercase;margin:0 0 9px;">
        Redes sociais
    </p>
    <div class="social-track" id="nexosn-social-track">
        @foreach ($socialLinks as $link)
        <a href="{{ $link->url }}" target="_blank" rel="noopener"
           class="spill {{ $socialPillClass(strtolower($link->url)) }}">
            <i data-lucide="{{ $link->lucide_icon }}" style="width:15px;height:15px;color:rgba(255,255,255,.88);flex-shrink:0;"></i>
            <span class="spill-label">{{ $link->label }}</span>
        </a>
        @endforeach
    </div>
    @if ($socialLinks->count() > 3)
    <div class="scroll-dots" id="nexosn-scroll-dots">
        <div class="sd on"></div>
        <div class="sd"></div>
        <div class="sd"></div>
    </div>
    @endif
</div>
<div style="height:1px;background:rgba(0,0,0,0.06);margin:0 14px;"></div>
@endif

<script>
(function () {
    const track = document.getElementById('nexosn-social-track');
    const dots  = document.querySelectorAll('#nexosn-scroll-dots .sd');
    if (!track) return;

    /* drag scroll no desktop */
    let drag = false, sx = 0, ss = 0;
    track.addEventListener('mousedown', e => { drag = true; sx = e.pageX; ss = track.scrollLeft; });
    document.addEventListener('mouseup', () => drag = false);
    document.addEventListener('mousemove', e => {
        if (!drag) return;
        e.preventDefault();
        track.scrollLeft = ss - (e.pageX - sx);
    });

    /* dots dinâmicos */
    if (dots.length) {
        track.addEventListener('scroll', () => {
            const max = track.scrollWidth - track.clientWidth;
            if (max <= 0) return;
            const s = track.scrollLeft / max < 0.33 ? 0 : track.scrollLeft / max < 0.66 ? 1 : 2;
            dots.forEach((d, i) => d.classList.toggle('on', i === s));
        });
    }
})();

function nexosnShare() {
    const url = '{{ url("/u/" . $card->slug) }}';
    const name = '{{ addslashes($card->display_name) }}';
    if (navigator.share) {
        navigator.share({ title: name, url }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url);
    }
}
</script>

{{-- ════════════ SEÇÕES ════════════ --}}
<div class="sections-wrap">

{{-- ── SOBRE ── --}}
@if ($card->bio)
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="align-left" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Sobre
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <p style="font-size:14px;color:#4b5563;line-height:1.65;margin:0;white-space:pre-line;">{{ $card->bio }}</p>
    </div>
</div>
@endif

{{-- ── CONTATO ── --}}
@if ($card->contact_phone || $card->contact_email || $card->website)
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="phone" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Contato
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        @if ($card->contact_phone)
        <a href="tel:{{ preg_replace('/\D/', '', $card->contact_phone) }}" class="crow">
            <i data-lucide="phone" style="width:15px;height:15px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span>{{ $card->contact_phone }}</span>
        </a>
        @endif

        @if ($card->contact_email)
        <a href="mailto:{{ $card->contact_email }}" class="crow">
            <i data-lucide="mail" style="width:15px;height:15px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span>{{ $card->contact_email }}</span>
        </a>
        @endif

        @if ($card->website)
        <a href="{{ $card->website }}" target="_blank" rel="noopener" class="crow">
            <i data-lucide="globe" style="width:15px;height:15px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span>{{ parse_url($card->website, PHP_URL_HOST) ?? $card->website }}</span>
        </a>
        @endif

    </div>
</div>
@endif

{{-- ── LOCALIZAÇÃO ── --}}
@if ($card->address)
@php $mapsUrl = 'https://maps.google.com/?q=' . urlencode($card->address); @endphp
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="map-pin" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Localização
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Linha de endereço clicável --}}
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
           style="display:flex;align-items:flex-start;gap:10px;padding:10px 12px;border-radius:10px;
                  background:#f7f7f6;text-decoration:none;margin-bottom:8px;">
            <i data-lucide="map-pin" style="width:15px;height:15px;color:var(--ui-icon);margin-top:1px;flex-shrink:0;"></i>
            <span style="font-size:13px;color:#374151;line-height:1.45;flex:1;">{{ $card->address }}</span>
            <i data-lucide="arrow-up-right" style="width:13px;height:13px;color:#d1d5db;flex-shrink:0;margin-top:2px;"></i>
        </a>

        {{-- Ações --}}
        <div style="display:flex;gap:7px;">
            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
               style="flex:1;display:flex;align-items:center;justify-content:center;gap:5px;
                      padding:12px;min-height:44px;border-radius:10px;font-size:12px;font-weight:600;
                      border:1px solid var(--ui-border);background:#fff;color:#4b5563;text-decoration:none;">
                <i data-lucide="map" style="width:14px;height:14px;color:var(--ui-icon);"></i>
                Google Maps
            </a>
            <button type="button" onclick="compartilharLocalizacao()"
                    style="flex:1;display:flex;align-items:center;justify-content:center;gap:5px;
                           padding:12px;min-height:44px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#4b5563;cursor:pointer;">
                <i data-lucide="share-2" style="width:14px;height:14px;color:var(--ui-icon);"></i>
                Compartilhar
            </button>
        </div>
        <div id="loc-copiado" style="display:none;margin-top:8px;text-align:center;font-size:12px;color:#16a34a;font-weight:600;">
            ✓ Link copiado!
        </div>
    </div>
</div>

<script>
function compartilharLocalizacao() {
    const url = '{{ $mapsUrl }}';
    if (navigator.share) {
        navigator.share({ title: '{{ addslashes($card->display_name) }} — Localização', text: '{{ addslashes($card->address) }}', url }).catch(()=>{});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const el = document.getElementById('loc-copiado');
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        });
    }
}
</script>
@endif

{{-- ── PIX ── --}}
@if ($card->pix_key)
<button type="button" onclick="copiarPix()" class="abtn abtn-pix" style="border-radius:16px;">
    <i data-lucide="qr-code" style="width:17px;height:17px;opacity:.75;"></i>
    Pagar via PIX
</button>
<div id="pix-copiado" style="display:none;text-align:center;font-size:12px;color:#16a34a;font-weight:600;margin-top:-2px;margin-bottom:2px;">
    ✓ Chave PIX copiada!
</div>
<script>
function copiarPix() {
    navigator.clipboard.writeText('{{ $card->pix_key }}').then(() => {
        const el = document.getElementById('pix-copiado');
        el.style.display = 'block';
        setTimeout(() => el.style.display = 'none', 3000);
    });
}
</script>
@endif

{{-- ── SERVIÇOS + PIX ── --}}
@php $activeServices = $card->services->where('is_active', true); @endphp
@if ($activeServices->isNotEmpty() && $card->pix_key)
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="receipt" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Serviços
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        @foreach ($activeServices as $service)
        <button type="button" class="svc-item w-full text-left"
                onclick="pixModal.open({{ $service->id }})">
            <div class="svc-icon">
                <svg data-lucide="{{ $service->lucide_icon }}" width="18" height="18" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <div class="svc-info">
                <div class="svc-name">{{ $service->name }}</div>
                @if ($service->description)
                <div class="svc-desc">{{ $service->description }}</div>
                @endif
            </div>
            <span class="svc-price">{{ $service->formatted_price }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
        @endforeach
    </div>
</div>
@endif

{{-- MODAL PIX --}}
<div id="pix-modal-overlay" class="pix-modal-overlay" onclick="pixModal.close()"></div>
<div id="pix-modal-sheet" class="pix-modal-sheet" role="dialog" aria-modal="true">
    <div class="pix-modal-handle"></div>
    <div id="pix-modal-body">
        <div class="pix-spinner" id="pix-spinner"></div>
    </div>
</div>

<script>
(function () {
    const slug    = '{{ $card->slug }}';
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
                        <p style="font-size:11px;color:#9ca3af;text-align:center;margin-bottom:12px;">
                            Abra o app do seu banco e escaneie o QR Code ou copie o código abaixo.
                        </p>
                        <div class="pix-payload-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span class="pix-payload-text" id="pix-payload-val">${data.payload}</span>
                        </div>
                        <button class="pix-copy-btn" onclick="pixModal.copy('${data.payload}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#431900" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Pix copia e cola
                        </button>
                        <p class="pix-feedback" id="pix-fb"></p>
                        <button onclick="pixModal.close()" style="display:block;width:100%;margin-top:10px;padding:12px;
                                border:none;background:#f3f4f6;border-radius:14px;font-size:13px;font-weight:600;
                                color:#6b7280;cursor:pointer;">Fechar</button>
                    `;
                })
                .catch(() => {
                    body.innerHTML = '<p style="text-align:center;font-size:13px;color:#ef4444;padding:24px 0;">Erro ao carregar. Tente novamente.</p>';
                });
        },

        copy(payload) {
            navigator.clipboard.writeText(payload).then(() => {
                const fb = document.getElementById('pix-fb');
                if (fb) { fb.textContent = '✓ Código copiado! Cole no seu banco.'; }
            });
        },

        close() {
            sheet.classList.remove('open');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        },
    };

    // Auto-open via link direto
    if (_autoOpen) {
        window.addEventListener('load', () => pixModal.open(_autoOpen));
    }

    // Swipe down para fechar
    let touchY = null;
    sheet.addEventListener('touchstart', e => { touchY = e.touches[0].clientY; }, { passive: true });
    sheet.addEventListener('touchend', e => {
        if (touchY !== null && (e.changedTouches[0].clientY - touchY) > 60) pixModal.close();
        touchY = null;
    }, { passive: true });
})();
</script>

{{-- ── GALERIA com Lightbox/Slideshow ── --}}
@if ($card->photos->isNotEmpty())
@php $photos = $card->photos; @endphp
<div class="cs" x-data="{ open: true }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="image" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Galeria
            <span style="font-size:10px;color:var(--ui-label);font-weight:500;">({{ $photos->count() }})</span>
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body-flush" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:3px;padding:0 0 12px;">
            @foreach ($photos as $i => $photo)
            <button type="button" onclick="nexosnGallery.open({{ $i }})"
               style="aspect-ratio:1;overflow:hidden;background:#f0f0ee;display:block;
                      border:none;padding:0;cursor:zoom-in;position:relative;">
                <img src="{{ $photo->thumbnail_url ?? Storage::url($photo->path) }}"
                     alt="{{ $photo->caption ?? 'Foto ' . ($i+1) }}"
                     loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .2s;"
                     onmouseenter="this.style.transform='scale(1.04)'" onmouseleave="this.style.transform=''">
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="nexosn-lightbox"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.94);
            flex-direction:column;align-items:center;justify-content:center;"
     onclick="if(event.target===this)nexosnGallery.close()">

    {{-- Toolbar --}}
    <div style="position:absolute;top:0;left:0;right:0;display:flex;align-items:center;justify-content:space-between;
                padding:14px 16px;background:linear-gradient(to bottom,rgba(0,0,0,.6),transparent);z-index:2;">
        <span id="lb-counter" style="font-size:12px;color:rgba(255,255,255,.6);font-weight:500;"></span>
        <button onclick="nexosnGallery.close()"
                style="background:rgba(255,255,255,.12);border:none;color:#fff;width:44px;height:44px;
                       border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:20px;">×</button>
    </div>

    {{-- Imagem --}}
    <div style="width:100%;max-height:80vh;display:flex;align-items:center;justify-content:center;padding:60px 56px;">
        <img id="lb-img" src="" alt="" style="max-width:100%;max-height:76vh;object-fit:contain;border-radius:8px;
             transition:opacity .18s;box-shadow:0 8px 48px rgba(0,0,0,.5);">
    </div>

    {{-- Caption --}}
    <div id="lb-caption" style="font-size:13px;color:rgba(255,255,255,.7);text-align:center;
                                 padding:0 24px 16px;min-height:20px;"></div>

    {{-- Prev --}}
    <button onclick="nexosnGallery.prev()"
            style="position:absolute;left:8px;top:50%;transform:translateY(-50%);
                   background:rgba(255,255,255,.12);border:none;color:#fff;
                   width:48px;height:48px;border-radius:50%;display:flex;align-items:center;
                   justify-content:center;cursor:pointer;font-size:22px;backdrop-filter:blur(4px);">‹</button>

    {{-- Next --}}
    <button onclick="nexosnGallery.next()"
            style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                   background:rgba(255,255,255,.12);border:none;color:#fff;
                   width:48px;height:48px;border-radius:50%;display:flex;align-items:center;
                   justify-content:center;cursor:pointer;font-size:22px;backdrop-filter:blur(4px);">›</button>

    {{-- Dots --}}
    <div id="lb-dots" style="display:flex;gap:6px;padding-bottom:20px;"></div>
</div>

<script>
(function () {
    @php
    $photosJson = $photos->map(fn($p) => [
        'src'     => Storage::url($p->path),
        'thumb'   => $p->thumbnail_url ?? Storage::url($p->path),
        'caption' => $p->caption ?? '',
    ]);
    @endphp
    const photos = @json($photosJson);

    const lb      = document.getElementById('nexosn-lightbox');
    const lbImg   = document.getElementById('lb-img');
    const lbCap   = document.getElementById('lb-caption');
    const lbCount = document.getElementById('lb-counter');
    const lbDots  = document.getElementById('lb-dots');
    let current   = 0;

    function buildDots() {
        lbDots.innerHTML = '';
        photos.forEach((_, i) => {
            const d = document.createElement('div');
            d.style.cssText = 'width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.35);cursor:pointer;transition:background .15s';
            d.onclick = () => nexosnGallery.go(i);
            lbDots.appendChild(d);
        });
    }

    function updateDots() {
        [...lbDots.children].forEach((d, i) => {
            d.style.background = i === current ? '#fff' : 'rgba(255,255,255,.35)';
            d.style.width = i === current ? '18px' : '6px';
            d.style.borderRadius = '99px';
        });
    }

    window.nexosnGallery = {
        open(idx) {
            current = idx;
            lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            buildDots();
            this.go(idx);
        },
        close() {
            lb.style.display = 'none';
            document.body.style.overflow = '';
        },
        go(idx) {
            if (idx < 0) idx = photos.length - 1;
            if (idx >= photos.length) idx = 0;
            current = idx;
            lbImg.style.opacity = '0';
            setTimeout(() => {
                lbImg.src     = photos[idx].src;
                lbImg.alt     = photos[idx].caption || 'Foto ' + (idx + 1);
                lbCap.textContent = photos[idx].caption;
                lbCount.textContent = (idx + 1) + ' / ' + photos.length;
                lbImg.style.opacity = '1';
                updateDots();
            }, 90);
        },
        prev() { this.go(current - 1); },
        next() { this.go(current + 1); },
    };

    // Teclado
    document.addEventListener('keydown', e => {
        if (lb.style.display === 'none') return;
        if (e.key === 'ArrowRight') nexosnGallery.next();
        if (e.key === 'ArrowLeft')  nexosnGallery.prev();
        if (e.key === 'Escape')     nexosnGallery.close();
    });

    // Swipe touch
    let touchX = null;
    lb.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend',   e => {
        if (touchX === null) return;
        const diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) diff > 0 ? nexosnGallery.next() : nexosnGallery.prev();
        touchX = null;
    }, { passive: true });
})();
</script>
@endif

{{-- ── SALVAR CONTATO (client-side — funciona offline) ── --}}
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
{{-- ── QR CODE / COMPARTILHAR (seção colapsável, offline-safe) ── --}}
<div class="cs" x-data="{ open: false }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="share-2" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Compartilhar perfil
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- QR Code SVG inline — sem requisição ao servidor --}}
        <div id="qr-inline" style="display:flex;justify-content:center;padding:10px 0 14px;">
            {!! $qrSvg !!}
        </div>

        {{-- Linha do link --}}
        <div style="display:flex;align-items:center;gap:8px;background:var(--ui-bg);
                    border:1px solid var(--ui-border);border-radius:10px;padding:10px 12px;margin-bottom:10px;">
            <i data-lucide="link" style="width:13px;height:13px;color:var(--ui-icon);flex-shrink:0;"></i>
            <span style="font-size:12px;color:#6b7280;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                nexosn.pageup.net.br/u/<strong style="color:#374151;">{{ $card->slug }}</strong>
            </span>
        </div>

        {{-- Botões de ação --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <button type="button" onclick="nexosnCopyLink(this)"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                           padding:12px;min-height:44px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#374151;cursor:pointer;">
                <i data-lucide="copy" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                <span>Copiar link</span>
            </button>
            <button type="button" onclick="nexosnShareNative()"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                           padding:12px;min-height:44px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#374151;cursor:pointer;">
                <i data-lucide="share-2" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                <span>Compartilhar</span>
            </button>
            <button type="button" onclick="nexosnDownloadQr()"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                           padding:11px 12px;border-radius:10px;font-size:12px;font-weight:600;
                           border:1px solid var(--ui-border);background:#fff;color:#374151;cursor:pointer;
                           grid-column:1/-1;">
                <i data-lucide="download" style="width:13px;height:13px;color:var(--ui-icon);"></i>
                <span>Baixar QR Code (PNG)</span>
            </button>
        </div>

        {{-- Feedback de cópia --}}
        <div id="share-feedback" style="display:none;text-align:center;font-size:12px;
                                        color:#16a34a;font-weight:600;margin-top:8px;"></div>
    </div>
</div>

<script>
const _profileUrl  = '{{ url('/u/' . $card->slug) }}';
const _profileName = '{{ addslashes($card->display_name) }}';
const _profileSlug = '{{ $card->slug }}';

// ── Copiar link offline-safe ──
function nexosnCopyLink(btn) {
    navigator.clipboard.writeText(_profileUrl).then(() => {
        const fb = document.getElementById('share-feedback');
        fb.textContent = '✓ Link copiado!';
        fb.style.display = 'block';
        setTimeout(() => fb.style.display = 'none', 3000);
    });
}

// ── Compartilhamento nativo (Web Share API) ──
function nexosnShareNative() {
    if (navigator.share) {
        navigator.share({ title: _profileName, url: _profileUrl }).catch(() => nexosnCopyLink());
    } else {
        nexosnCopyLink(null);
    }
}

// ── Download QR PNG via Canvas (sem servidor) ──
function nexosnDownloadQr() {
    const svgEl = document.querySelector('#qr-inline svg');
    if (!svgEl) return;
    const svgData = new XMLSerializer().serializeToString(svgEl);
    const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
    const url     = URL.createObjectURL(svgBlob);
    const img     = new Image();
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
        a.download = 'qr-' + _profileSlug + '.png';
        a.click();
    };
    img.src = url;
}

// ── Download vCard client-side (VCF 3.0, sem servidor) ──
function nexosnDownloadVCard() {
    const raw = document.getElementById('vcard-data');
    if (!raw) return;
    const d = JSON.parse(raw.textContent);
    const lines = [
        'BEGIN:VCARD',
        'VERSION:3.0',
        'FN:' + d.fn,
    ];
    if (d.title) lines.push('TITLE:' + d.title);
    if (d.org)   lines.push('ORG:' + d.org);
    if (d.tel)   lines.push('TEL;TYPE=CELL:+55' + d.tel);
    if (d.email) lines.push('EMAIL:' + d.email);
    if (d.url)   lines.push('URL:' + d.url);
    if (d.note)  lines.push('NOTE:' + d.note.replace(/\n/g, '\\n'));
    lines.push('END:VCARD');
    const vcf  = lines.join('\r\n');
    const blob = new Blob([vcf], { type: 'text/vcard;charset=utf-8' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = d.slug + '.vcf';
    a.click();
    setTimeout(() => URL.revokeObjectURL(a.href), 5000);
}
</script>

{{-- ── AGENDAR (Pro) ── --}}
@if ($card->schedule && $card->schedule->is_active)
<div class="cs" x-data="{ open: false }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="calendar" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Agendar horário
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body-flush" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div style="padding:0 0 12px;">
            <livewire:schedule.appointment-calendar :card="$card" />
        </div>
    </div>
</div>
@endif

{{-- ── FORMULÁRIO ── --}}
<div class="cs" x-data="{ open: false }">
    <button class="cs-head" type="button" @click="open = !open">
        <div class="cs-label">
            <i data-lucide="message-square" style="width:13px;height:13px;color:var(--ui-icon);"></i>
            Enviar mensagem
        </div>
        <i data-lucide="chevron-down" class="cs-chevron"
           :style="open ? 'transform:rotate(180deg)' : ''"></i>
    </button>
    <div class="cs-body" x-show="open"
         x-transition:enter="transition ease-out duration-180"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <livewire:card.contact-form :card="$card" />
    </div>
</div>

</div>{{-- /sections-wrap --}}

{{-- ═══════════════════ RODAPÉ / MARCA D'ÁGUA ═══════════════════ --}}
<div style="margin-top:8px;border-top:1px solid var(--ui-border);background:#EAE2B7;">
    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:{{ $card->show_watermark ? 'space-between' : 'center' }};">

        @if ($card->show_watermark)
        <span style="font-size:11px;color:#9c9585;font-weight:400;letter-spacing:.1px;">
            Criado com <strong style="color:#52514E;font-weight:700;">NEXOSN</strong>
        </span>
        @endif

        <a href="https://nexosn.pageup.net.br" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:5px;text-decoration:none;
                  opacity:.55;transition:opacity .2s;"
           onmouseenter="this.style.opacity='1'" onmouseleave="this.style.opacity='.55'"
           title="NEXOSN — Identidade digital">
            <svg viewBox="0 0 16 16" fill="none" style="width:11px;height:11px;flex-shrink:0;">
                <line x1="3" y1="3"  x2="3"  y2="13" stroke="#52514E" stroke-width="1.75" stroke-linecap="round"/>
                <line x1="3" y1="3"  x2="13" y2="13" stroke="#52514E" stroke-width="1.75" stroke-linecap="round"/>
                <line x1="13" y1="3" x2="13" y2="13" stroke="#52514E" stroke-width="1.75" stroke-linecap="round"/>
                <circle cx="3"  cy="3"  r="2" fill="#52514E"/>
                <circle cx="3"  cy="13" r="2" fill="#52514E"/>
                <circle cx="13" cy="3"  r="2" fill="#52514E"/>
                <circle cx="13" cy="13" r="2" fill="#52514E"/>
            </svg>
            <span style="font-family:'Inter',sans-serif;font-size:11px;font-weight:700;
                         color:#52514E;letter-spacing:.02em;line-height:1;">NEX<span style="opacity:.5;">OSN</span></span>
        </a>

    </div>
</div>

{{-- ═══════════════════ BANNER DE COOKIES ═══════════════════ --}}
<div id="cookie-banner"
     style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9990;
            background:#1a1f2e;padding:14px 16px 20px;box-shadow:0 -4px 24px rgba(0,0,0,.25);">
    <p style="font-size:12px;color:rgba(255,255,255,.8);line-height:1.55;margin:0 0 12px;">
        {!! \App\Models\AppSetting::get('cookie_banner_text', 'Usamos cookies essenciais. Ao continuar, você concorda com nossa <a href="/legal/cookies" style="color:#FCBF49;">Política de Cookies</a>.') !!}
    </p>
    <div style="display:flex;gap:8px;">
        <button onclick="cookieConsent('all')"
                style="flex:1;padding:10px;border-radius:10px;border:none;cursor:pointer;
                       font-size:12px;font-weight:700;background:#FCBF49;color:#1a1f2e;">
            Aceitar todos
        </button>
        <button onclick="cookieConsent('essential')"
                style="flex:1;padding:10px;border-radius:10px;border:none;cursor:pointer;
                       font-size:12px;font-weight:600;background:rgba(255,255,255,.1);color:rgba(255,255,255,.85);">
            Apenas essenciais
        </button>
    </div>
</div>
<script>
(function () {
    if (!localStorage.getItem('cookie_consent')) {
        document.getElementById('cookie-banner').style.display = 'block';
    }
})();
function cookieConsent(choice) {
    localStorage.setItem('cookie_consent', choice);
    document.getElementById('cookie-banner').style.display = 'none';
}
</script>

@endsection
