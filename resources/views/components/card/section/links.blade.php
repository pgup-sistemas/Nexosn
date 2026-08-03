@props(['card'])
@php $links = $card->links->where('is_active', true); @endphp
@if ($links->isNotEmpty())
<div class="cs">
    <h2 class="cs-title"><i data-lucide="link" class="w-4 h-4"></i> Links</h2>
    <div class="campaign-link-list">
        @foreach ($links as $link)
            <a href="{{ route('card.link.click', ['slug' => $card->slug, 'linkId' => $link->id]) }}" target="_blank" rel="noopener" class="campaign-link-item">
                <i data-lucide="{{ $link->lucide_icon }}" class="w-4 h-4"></i>
                <span>{{ $link->label }}</span>
            </a>
        @endforeach
    </div>
</div>
@endif
