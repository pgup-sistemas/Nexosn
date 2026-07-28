<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Services\EfiBankService;
use Illuminate\Console\Command;
use Throwable;

class EfiBankSetupPlans extends Command
{
    protected $signature = 'efibank:setup-plans';

    protected $description = 'Cria os planos de assinatura Pro (mensal e anual) na Efi Bank — rodar uma única vez';

    public function handle(EfiBankService $efiBank): int
    {
        if (!config('services.efibank.client_id') || !config('services.efibank.client_secret')) {
            $this->error('EFI_CLIENT_ID / EFI_CLIENT_SECRET não configurados no .env.');
            return self::FAILURE;
        }

        try {
            $this->info('Criando plano mensal...');
            $monthly = $efiBank->createPlan('NEXOSN Pro — Mensal', intervalMonths: 1);
            $monthlyId = $monthly['data']['plan_id'] ?? null;
            $this->line('  plan_id: ' . ($monthlyId ?? '(não retornado — veja a resposta completa abaixo)'));

            $this->info('Criando plano anual...');
            $annual = $efiBank->createPlan('NEXOSN Pro — Anual', intervalMonths: 12);
            $annualId = $annual['data']['plan_id'] ?? null;
            $this->line('  plan_id: ' . ($annualId ?? '(não retornado — veja a resposta completa abaixo)'));

            $this->newLine();
            if ($monthlyId && $annualId) {
                $this->info("Plan IDs gerados: mensal={$monthlyId}  anual={$annualId}");

                try {
                    AppSetting::set('efi_plan_id_monthly', (string) $monthlyId);
                    AppSetting::set('efi_plan_id_annual', (string) $annualId);
                    $this->info('IDs salvos em Admin → Configurações (AppSetting).');
                } catch (Throwable $dbEx) {
                    $this->warn('Não foi possível salvar no banco: ' . $dbEx->getMessage());
                    $this->info('Adicione manualmente ao .env (ou via Admin → Configurações):');
                    $this->line("  EFI_PLAN_ID_MONTHLY={$monthlyId}");
                    $this->line("  EFI_PLAN_ID_ANNUAL={$annualId}");
                }
            } else {
                $this->warn('Não foi possível extrair os plan_ids. Resposta completa:');
                $this->line(json_encode(['monthly' => $monthly, 'annual' => $annual], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        } catch (Throwable $e) {
            $this->error('Falha ao criar planos: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
