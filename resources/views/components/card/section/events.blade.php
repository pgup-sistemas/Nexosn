@props(['card'])
@php $events = $card->campaignEvents->where('is_active', true)->where('event_date', '>=', now()->startOfDay()); @endphp
@if ($events->isNotEmpty())
<x-card.section.collapsible icon="calendar-days" title="Agenda">
    <div class="campaign-event-list">
        @foreach ($events as $event)
            <div class="campaign-event-item">
                <div class="campaign-event-date">
                    <span class="campaign-event-day">{{ $event->event_date->format('d') }}</span>
                    <span class="campaign-event-month">{{ $event->event_date->translatedFormat('M') }}</span>
                </div>
                <div>
                    <h4 class="campaign-event-title">{{ $event->title }}</h4>
                    @if ($event->event_time)
                        <p class="campaign-event-meta"><i data-lucide="clock" class="w-3.5 h-3.5"></i> {{ substr($event->event_time, 0, 5) }}</p>
                    @endif
                    @if ($event->location)
                        <p class="campaign-event-meta"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $event->location }}</p>
                    @endif
                    @if ($event->description)
                        <p class="campaign-event-desc">{{ $event->description }}</p>
                    @endif
                    @if ($event->map_url)
                        <a href="{{ $event->map_url }}" target="_blank" rel="noopener" class="campaign-event-link">
                            <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Como chegar
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-card.section.collapsible>
@endif
