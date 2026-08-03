@props(['card'])
@php $items = $card->campaignTimelineItems; @endphp
@if ($items->isNotEmpty())
<div class="cs">
    <h2 class="cs-title"><i data-lucide="history" class="w-4 h-4"></i> Linha do Tempo</h2>
    <div class="campaign-timeline">
        @foreach ($items as $item)
            <div class="campaign-timeline-item">
                <div class="campaign-timeline-marker">
                    <i data-lucide="{{ $item->icon ?: 'flag' }}" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <p class="campaign-timeline-date">{{ $item->occurred_on->format('d/m/Y') }}</p>
                    <h4 class="campaign-timeline-title">{{ $item->title }}</h4>
                    @if ($item->description)
                        <p class="campaign-timeline-desc">{{ $item->description }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
