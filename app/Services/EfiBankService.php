<?php

namespace App\Services;

use App\Mail\PlanDowngradedMail;
use App\Models\AppSetting;
use App\Models\User;
use Efi\EfiPay;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class EfiBankService
{
    protected function options(): array
    {
        try {
            $sandbox = (bool) AppSetting::get('efi_sandbox', true);
        } catch (\Throwable) {
            $sandbox = (bool) env('EFI_SANDBOX', true);
        }

        return [
            'clientId'       => config('services.efibank.client_id'),
            'clientSecret'   => config('services.efibank.client_secret'),
            'certificate'    => config('services.efibank.certificate'),
            'pwdCertificate' => config('services.efibank.certificate_pwd', ''),
            'sandbox'        => $sandbox,
            'timeout'        => 30,
        ];
    }

    protected function client(): EfiPay
    {
        return new EfiPay($this->options());
    }

    /**
     * Cria um plano de assinatura na Efi. Uso único (via `php artisan efibank:setup-plans`).
     */
    public function createPlan(string $name, int $intervalMonths = 1, ?int $repeats = null): array
    {
        return (array) $this->client()->createPlan([], [
            'name'     => $name,
            'interval' => $intervalMonths,
            'repeats'  => $repeats,
        ]);
    }

    /**
     * Gera o link de pagamento hospedado da Efi para o plano Pro (mensal ou anual)
     * e devolve a URL para redirecionar o titular.
     */
    public function createCheckoutLink(User $user, string $planType): string
    {
        $settingKey = $planType === 'annual' ? 'efi_plan_id_annual' : 'efi_plan_id_monthly';
        $planId = AppSetting::get($settingKey)
            ?: ($planType === 'annual'
                ? config('services.efibank.plan_id_annual')
                : config('services.efibank.plan_id_monthly'));

        if (!$planId) {
            throw new RuntimeException(
                'Plano Efi Bank não configurado. Rode "php artisan efibank:setup-plans" ou configure os IDs em Admin → Configurações.'
            );
        }

        $price = (float) AppSetting::get('plan_price_monthly', 29.90);
        if ($planType === 'annual') {
            $price = round($price * 12 * 0.75, 2); // 25% de desconto anual (mesmo destaque da home/planos)
        }

        $body = [
            'items' => [[
                'name'   => $planType === 'annual' ? 'NEXOSN Pro — Anual' : 'NEXOSN Pro — Mensal',
                'value'  => (int) round($price * 100), // Efi trabalha em centavos
                'amount' => 1,
            ]],
            'settings' => [
                'payment_method' => 'all',
            ],
            'metadata' => [
                'custom_id'        => "user-{$user->id}",
                'notification_url' => route('webhook.efibank'),
            ],
        ];

        $response = $this->fetchCheckoutLink(['id' => $planId], $body);
        $url = $response['data']['payment_url'] ?? $response['payment_url'] ?? null;

        if (!$url) {
            Log::error('efibank.checkout_link_missing_url', ['response' => $response]);
            throw new RuntimeException('Efi Bank não retornou o link de pagamento.');
        }

        return $url;
    }

    protected function fetchCheckoutLink(array $params, array $body): array
    {
        return (array) $this->client()->createOneStepSubscriptionLink($params, $body);
    }

    /**
     * Ponto de entrada do webhook: a Efi só envia um token de notificação — os dados
     * reais do evento precisam ser buscados de volta na API (GET /v1/notification/{token}).
     */
    public function handleWebhookToken(string $token): void
    {
        $notification = $this->fetchNotification($token);

        foreach (($notification['data'] ?? []) as $event) {
            $this->processEvent($event);
        }
    }

    protected function fetchNotification(string $token): array
    {
        return (array) $this->client()->getNotification(['token' => $token]);
    }

    protected function fetchSubscriptionDetail(int $subscriptionId): array
    {
        return (array) $this->client()->detailSubscription(['id' => $subscriptionId]);
    }

    protected function processEvent(array $event): void
    {
        $type = $event['type'] ?? null;
        if (!in_array($type, ['subscription', 'subscription_charge'], true)) {
            return;
        }

        $subscriptionId = $event['identifiers']['subscription_id'] ?? null;
        $status         = $event['status']['current'] ?? null;

        if (!$subscriptionId || !$status) {
            Log::warning('efibank.webhook_event_incomplete', ['event' => $event]);
            return;
        }

        $user = $this->resolveUserFromSubscription((int) $subscriptionId);
        if (!$user) {
            return;
        }

        Log::info('efibank.webhook_event', [
            'user_id'         => $user->id,
            'subscription_id' => $subscriptionId,
            'status'          => $status,
        ]);

        match ($status) {
            'active', 'paid'       => $this->activate($user, (int) $subscriptionId),
            'canceled', 'expired'  => $this->downgradeAndNotify($user),
            default                => null,
        };
    }

    private function resolveUserFromSubscription(int $subscriptionId): ?User
    {
        $detail   = $this->fetchSubscriptionDetail($subscriptionId);
        $customId = $detail['data']['custom_id'] ?? null;

        if (!$customId || !str_starts_with($customId, 'user-')) {
            Log::warning('efibank.webhook_customid_missing', [
                'subscription_id' => $subscriptionId,
                'detail'          => $detail,
            ]);
            return null;
        }

        $userId = (int) substr($customId, strlen('user-'));
        $user   = User::find($userId);

        if (!$user) {
            Log::warning('efibank.webhook_user_not_found', [
                'user_id'         => $userId,
                'subscription_id' => $subscriptionId,
            ]);
        }

        return $user;
    }

    private function activate(User $user, int $subscriptionId): void
    {
        $detail   = $this->fetchSubscriptionDetail($subscriptionId);
        $interval = $detail['data']['plan']['interval'] ?? 1;

        app(PlanService::class)->activatePro($user, (string) $subscriptionId, now()->addMonths((int) $interval));
    }

    private function downgradeAndNotify(User $user): void
    {
        app(PlanService::class)->downgradeToFree($user);
        Mail::to($user->email)->send(new PlanDowngradedMail($user));
    }
}
