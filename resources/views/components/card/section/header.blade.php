@props(['card'])
@php $profile = $card->campaignProfile; @endphp
<div class="cs cs-campaign-header">
    @if ($card->cover_photo)
        <div class="campaign-cover" style="background-image: url('{{ \Illuminate\Support\Facades\Storage::url($card->cover_photo) }}')"></div>
    @endif

    <div class="campaign-header-body">
        @if ($profile?->portrait_photo)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($profile->portrait_photo) }}" alt="{{ $card->display_name }}" class="campaign-portrait">
        @elseif ($card->profile_photo)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($card->profile_photo) }}" alt="{{ $card->display_name }}" class="campaign-portrait">
        @endif

        <h1 class="campaign-name">{{ $profile?->campaign_name ?: $card->display_name }}</h1>

        @if ($profile?->role_title || $profile?->ballot_number)
            <p class="campaign-role">
                {{ $profile?->role_title }}
                @if ($profile?->ballot_number)
                    <span class="campaign-ballot" style="background-color: var(--card-button)">{{ $profile->ballot_number }}</span>
                @endif
            </p>
        @endif

        @if ($profile?->organization_name || $profile?->affiliation)
            <p class="campaign-org">{{ $profile?->organization_name }}{{ $profile?->affiliation ? ' · ' . $profile->affiliation : '' }}</p>
        @endif

        @if ($profile?->slogan)
            <p class="campaign-slogan">"{{ $profile->slogan }}"</p>
        @endif
    </div>

    @if ($profile?->countdown_target_at && $profile->countdown_target_at->isFuture())
        <div class="campaign-countdown" data-target="{{ $profile->countdown_target_at->toIso8601String() }}">
            <span data-unit="days">--</span>d <span data-unit="hours">--</span>h <span data-unit="minutes">--</span>m
        </div>
        <script>
        (function () {
            var el = document.currentScript.previousElementSibling;
            var target = new Date(el.dataset.target).getTime();
            function tick() {
                var diff = target - Date.now();
                if (diff <= 0) { el.textContent = 'Chegou o dia!'; return; }
                var d = Math.floor(diff / 86400000);
                var h = Math.floor((diff % 86400000) / 3600000);
                var m = Math.floor((diff % 3600000) / 60000);
                el.querySelector('[data-unit="days"]').textContent = d;
                el.querySelector('[data-unit="hours"]').textContent = h;
                el.querySelector('[data-unit="minutes"]').textContent = m;
                setTimeout(tick, 60000);
            }
            tick();
        })();
        </script>
    @endif
</div>
