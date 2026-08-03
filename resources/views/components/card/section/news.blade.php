@props(['card'])
@php $items = $card->campaignNews->where('is_active', true); @endphp
@if ($items->isNotEmpty())
<x-card.section.collapsible icon="newspaper" title="Notícias">
    <div class="campaign-news-list">
        @foreach ($items as $news)
            <div class="campaign-news-item">
                @if ($news->cover_image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($news->cover_image) }}" alt="{{ $news->title }}" class="campaign-news-image">
                @endif
                <div>
                    <h4 class="campaign-news-title">{{ $news->title }}</h4>
                    @if ($news->published_at)
                        <p class="campaign-news-date">{{ $news->published_at->format('d/m/Y') }}</p>
                    @endif
                    @if ($news->body)
                        <p class="campaign-news-body">{{ \Illuminate\Support\Str::limit($news->body, 180) }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-card.section.collapsible>
@endif
