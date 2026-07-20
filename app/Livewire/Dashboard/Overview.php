<?php

namespace App\Livewire\Dashboard;

use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $user = auth()->user();
        $card = $user->card;

        $stats = [
            'views_total'  => $card ? $card->views()->count() : 0,
            'views_7days'  => $card ? $card->views()->where('created_at', '>=', now()->subDays(7))->count() : 0,
            'links_count'  => $card ? $card->links()->count() : 0,
            'photos_count' => $card ? $card->photos()->count() : 0,
        ];

        // Gráfico de visitas — últimos 30 dias agrupados por dia
        $viewsChart = [];
        if ($card) {
            $rows = $card->views()
                ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');

            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $viewsChart[] = [
                    'date'  => now()->subDays($i)->format('d/m'),
                    'total' => $rows[$date] ?? 0,
                ];
            }
        }

        // Origem do tráfego — últimos 30 dias
        $sources = [];
        if ($card) {
            $total = $card->views()->where('created_at', '>=', now()->subDays(30))->count();
            if ($total > 0) {
                $rows = $card->views()
                    ->where('created_at', '>=', now()->subDays(30))
                    ->selectRaw('source, COUNT(*) as total')
                    ->groupBy('source')
                    ->orderByDesc('total')
                    ->get();

                foreach ($rows as $row) {
                    $sources[] = [
                        'label'   => self::sourceLabel($row->source),
                        'source'  => $row->source,
                        'total'   => $row->total,
                        'pct'     => round($row->total / $total * 100),
                    ];
                }
            }
        }

        // Clicks por link — top 10
        $topLinks = [];
        if ($card) {
            $topLinks = $card->links()
                ->where('is_active', true)
                ->orderByDesc('click_count')
                ->limit(10)
                ->get(['label', 'url', 'click_count']);
        }

        $planLabel   = $user->isOnTrial() ? 'Pro (Trial)' : ucfirst($user->plan);
        $trialEndsAt = $user->trial_ends_at;
        $isPro       = $user->isPro() || $user->isOnTrial();

        return view('livewire.dashboard.overview',
            compact('card', 'stats', 'planLabel', 'trialEndsAt', 'isPro', 'viewsChart', 'sources', 'topLinks')
        );
    }

    private static function sourceLabel(string $source): string
    {
        return match ($source) {
            'direct'    => 'Acesso direto',
            'whatsapp'  => 'WhatsApp',
            'instagram' => 'Instagram',
            'google'    => 'Google',
            'facebook'  => 'Facebook',
            'linkedin'  => 'LinkedIn',
            'twitter'   => 'Twitter / X',
            'tiktok'    => 'TikTok',
            'telegram'  => 'Telegram',
            default     => 'Outros',
        };
    }
}
