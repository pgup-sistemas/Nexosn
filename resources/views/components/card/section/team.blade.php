@props(['card'])
@php $members = $card->campaignTeamMembers->where('is_active', true); @endphp
@if ($members->isNotEmpty())
<x-card.section.collapsible icon="users" title="Equipe">
    <div class="campaign-team-grid">
        @foreach ($members as $member)
            <div class="campaign-team-member">
                @if ($member->photo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($member->photo) }}" alt="{{ $member->name }}" class="campaign-team-photo">
                @else
                    <div class="campaign-team-photo campaign-team-photo-placeholder">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                @endif
                <p class="campaign-team-name">{{ $member->name }}</p>
                @if ($member->role)
                    <p class="campaign-team-role">{{ $member->role }}</p>
                @endif
            </div>
        @endforeach
    </div>
</x-card.section.collapsible>
@endif
