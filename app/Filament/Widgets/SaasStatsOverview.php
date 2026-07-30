<?php

namespace App\Filament\Widgets;

use App\Models\AppSetting;
use App\Models\Card;
use App\Models\Invoice;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaasStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $price = (float) AppSetting::get('plan_price_monthly', 19.90);

        // Usuários
        $totalUsers = User::count();
        $novosHoje  = User::whereDate('created_at', today())->count();
        $novosMes   = User::whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)->count();

        // Pro pagos (não trial)
        $proPagos = User::where('plan', 'pro')
            ->whereNotNull('plan_expires_at')
            ->where('plan_expires_at', '>', now())
            ->count();

        // Trial ativo
        $emTrial = User::where('plan', 'pro')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now())
            ->whereNull('plan_expires_at')
            ->count();

        // Trial expirado sem conversão (churn de trial)
        $churnTrial = User::where('plan', 'free')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->count();

        // Taxa de conversão (trial → pago)
        $totalTrials = $proPagos + $emTrial + $churnTrial;
        $conversion  = $totalTrials > 0 ? round(($proPagos / $totalTrials) * 100, 1) : 0;

        // Receita
        $mrr    = $proPagos * $price;
        $arr    = $mrr * 12;
        $receitaMes = Invoice::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // Cartões
        $totalCards  = Card::count();
        $activeCards = Card::where('is_active', true)->count();

        return [
            Stat::make('Total de usuários', number_format($totalUsers))
                ->description("+{$novosHoje} hoje · +{$novosMes} este mês")
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Pro pago', number_format($proPagos))
                ->description("Trial ativo: {$emTrial} · Churn trial: {$churnTrial}")
                ->color('success')
                ->icon('heroicon-o-star'),

            Stat::make('Conversão trial → pago', "{$conversion}%")
                ->description("{$proPagos} de {$totalTrials} trials convertidos")
                ->color($conversion >= 20 ? 'success' : ($conversion >= 10 ? 'warning' : 'danger'))
                ->icon('heroicon-o-arrow-trending-up'),

            Stat::make('MRR', 'R$ ' . number_format($mrr, 2, ',', '.'))
                ->description('ARR projetado: R$ ' . number_format($arr, 2, ',', '.'))
                ->color('warning')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Receita este mês', 'R$ ' . number_format((float) $receitaMes, 2, ',', '.'))
                ->description('Faturas pagas no mês corrente')
                ->color('success')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Cartões ativos', number_format($activeCards) . ' / ' . number_format($totalCards))
                ->description(number_format($totalCards - $activeCards) . ' suspensos')
                ->color('info')
                ->icon('heroicon-o-identification'),
        ];
    }
}
