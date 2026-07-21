<?php

namespace App\Services;

use App\Models\User;

class PlanService
{
    const TRIAL_DAYS = 14;

    const LIMITS = [
        'free' => ['links' => 5,  'photos' => 3,  'services' => 3],
        'pro'  => ['links' => -1, 'photos' => 30, 'services' => -1],
    ];

    public function activateTrial(User $user): void
    {
        if ($user->trial_ends_at !== null) {
            return;
        }

        $user->update([
            'plan'          => 'pro',
            'trial_ends_at' => now()->addDays(self::TRIAL_DAYS),
        ]);
    }

    public function withinLimit(User $user, string $feature, int $current): bool
    {
        $plan  = $user->isPro() ? 'pro' : 'free';
        $limit = self::LIMITS[$plan][$feature] ?? 0;

        return $limit === -1 || $current < $limit;
    }

    public function activatePro(User $user, string $subscriptionId, ?\Carbon\Carbon $expiresAt = null): void
    {
        $user->update([
            'plan'                  => 'pro',
            'efi_subscription_id'   => $subscriptionId,
            'plan_expires_at'       => $expiresAt ?? now()->addMonth(),
        ]);
    }

    public function downgradeToFree(User $user): void
    {
        $user->update([
            'plan'            => 'free',
            'plan_expires_at' => null,
        ]);
    }
}
