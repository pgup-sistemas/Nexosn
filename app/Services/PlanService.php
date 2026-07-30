<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PlanService
{
    public static function trialDays(): int
    {
        return (int) AppSetting::get('trial_days', 14);
    }

    public static function limits(string $plan): array
    {
        if ($plan === 'pro') {
            return [
                'links'    => -1,
                'photos'   => (int) AppSetting::get('limit_pro_photos', 30),
                'services' => (int) AppSetting::get('limit_pro_services', 20),
            ];
        }
        return [
            'links'    => (int) AppSetting::get('limit_free_links', 5),
            'photos'   => (int) AppSetting::get('limit_free_photos', 3),
            'services' => (int) AppSetting::get('limit_free_services', 3),
        ];
    }

    public function activateTrial(User $user): void
    {
        if ($user->trial_ends_at !== null) {
            return;
        }

        $user->update([
            'plan'          => 'pro',
            'trial_ends_at' => now()->addDays(self::trialDays()),
        ]);
    }

    public function withinLimit(User $user, string $feature, int $current): bool
    {
        $plan  = $user->isPro() ? 'pro' : 'free';
        $limit = self::limits($plan)[$feature] ?? 0;

        return $limit === -1 || $current < $limit;
    }

    public function activatePro(User $user, string $subscriptionId, ?\Carbon\Carbon $expiresAt = null): void
    {
        $user->update([
            'plan'                => 'pro',
            'efi_subscription_id' => $subscriptionId,
            'plan_expires_at'     => $expiresAt ?? now()->addMonth(),
        ]);
    }

    public function downgradeToFree(User $user): void
    {
        $user->update([
            'plan'            => 'free',
            'plan_expires_at' => null,
        ]);
    }

    // ── Ações manuais (admin) ────────────────────────────────────────────────

    public function adminActivatePro(User $user, string $planType = 'monthly', int $months = 1, ?string $note = null): void
    {
        $expiresAt = now()->addMonths($months);

        $user->update([
            'plan'            => 'pro',
            'plan_expires_at' => $expiresAt,
        ]);

        // Fatura manual para rastreio
        Invoice::create([
            'user_id'     => $user->id,
            'plan_type'   => $planType,
            'amount'      => $this->priceForPlan($planType),
            'status'      => 'paid',
            'description' => 'Ativação manual pelo admin' . ($note ? ": {$note}" : ''),
            'paid_at'     => now(),
        ]);

        $this->auditPlanChange($user, 'plan_activated', [
            'plan_type'  => $planType,
            'expires_at' => $expiresAt->toDateString(),
            'note'       => $note,
        ]);
    }

    public function adminExtendTrial(User $user, int $days = 7, ?string $note = null): void
    {
        $base = ($user->trial_ends_at && $user->trial_ends_at->isFuture())
            ? $user->trial_ends_at
            : now();

        $newDate = $base->copy()->addDays($days);

        $user->update([
            'plan'          => 'pro',
            'trial_ends_at' => $newDate,
        ]);

        $this->auditPlanChange($user, 'trial_extended', [
            'days'     => $days,
            'new_date' => $newDate->toDateString(),
            'note'     => $note,
        ]);
    }

    public function adminDowngradeToFree(User $user, ?string $note = null): void
    {
        $this->downgradeToFree($user);

        $this->auditPlanChange($user, 'plan_downgraded', [
            'note' => $note,
        ]);
    }

    private function auditPlanChange(User $user, string $action, array $payload = []): void
    {
        AuditLog::create([
            'action'     => $action,
            'admin_id'   => Auth::id(),
            'target_id'  => $user->id,
            'ip_address' => request()->ip(),
            'payload'    => $payload,
        ]);
    }

    private function priceForPlan(string $planType): float
    {
        $monthly = (float) AppSetting::get('plan_price_monthly', 19.90);
        return $planType === 'annual' ? round($monthly * 12 * 0.75, 2) : $monthly;
    }
}
