@props(['card'])
@php $proposals = $card->campaignProposals->where('is_active', true); @endphp
@if ($proposals->isNotEmpty())
<div class="cs">
    <h2 class="cs-title"><i data-lucide="list-checks" class="w-4 h-4"></i> Propostas</h2>
    <div class="campaign-proposal-list">
        @foreach ($proposals->groupBy(fn ($p) => $p->category?->name ?? 'Geral') as $categoryName => $items)
            <div class="campaign-proposal-group">
                @if ($proposals->pluck('category_id')->filter()->isNotEmpty())
                    <h3 class="campaign-proposal-category">{{ $categoryName }}</h3>
                @endif
                @foreach ($items as $proposal)
                    <div class="campaign-proposal-item">
                        @if ($proposal->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($proposal->image) }}" alt="{{ $proposal->title }}" class="campaign-proposal-image">
                        @endif
                        <h4 class="campaign-proposal-title">{{ $proposal->title }}</h4>
                        @if ($proposal->description)
                            <p class="campaign-proposal-desc">{{ $proposal->description }}</p>
                        @endif
                        @if ($proposal->video_url)
                            <a href="{{ $proposal->video_url }}" target="_blank" rel="noopener" class="campaign-proposal-link">
                                <i data-lucide="play-circle" class="w-4 h-4"></i> Assistir vídeo
                            </a>
                        @endif
                        @if ($proposal->pdf_path)
                            <a href="{{ route('card.proposal.pdf', ['card' => $card->slug, 'proposal' => $proposal->id]) }}" target="_blank" class="campaign-proposal-link">
                                <i data-lucide="file-text" class="w-4 h-4"></i> Ver documento
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endif
