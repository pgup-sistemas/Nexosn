<?php

namespace App\Livewire\Dashboard;

use App\Models\Card;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $user = auth()->user();
        $card = $user->card;

        $stats = [
            'views_total'    => $card ? $card->views()->count() : 0,
            'views_7days'    => $card ? $card->views()->where('created_at', '>=', now()->subDays(7))->count() : 0,
            'links_count'    => $card ? $card->links()->count() : 0,
            'photos_count'   => $card ? $card->photos()->count() : 0,
        ];

        $planLabel   = $user->isOnTrial() ? 'Pro (Trial)' : ucfirst($user->plan);
        $trialEndsAt = $user->trial_ends_at;
        $isPro       = $user->isPro() || $user->isOnTrial();

        return view('livewire.dashboard.overview', compact('card', 'stats', 'planLabel', 'trialEndsAt', 'isPro'));
    }
}
