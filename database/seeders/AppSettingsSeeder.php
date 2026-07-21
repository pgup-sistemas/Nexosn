<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Negócio ──────────────────────────────────────────────────────
            ['key' => 'plan_price_monthly',  'value' => '29.90',  'type' => 'float',   'group' => 'negocio',     'label' => 'Preço Pro mensal (R$)'],
            ['key' => 'trial_days',          'value' => '14',     'type' => 'integer', 'group' => 'negocio',     'label' => 'Dias de trial gratuito'],
            ['key' => 'limit_free_links',    'value' => '5',      'type' => 'integer', 'group' => 'negocio',     'label' => 'Limite de links (Free)'],
            ['key' => 'limit_free_photos',   'value' => '3',      'type' => 'integer', 'group' => 'negocio',     'label' => 'Limite de fotos (Free)'],
            ['key' => 'limit_free_services', 'value' => '3',      'type' => 'integer', 'group' => 'negocio',     'label' => 'Limite de serviços (Free)'],
            ['key' => 'maintenance_mode',    'value' => '0',      'type' => 'boolean', 'group' => 'negocio',     'label' => 'Modo manutenção'],

            // ── E-mail ───────────────────────────────────────────────────────
            ['key' => 'support_email',       'value' => 'suporte@nexosn.pageup.net.br', 'type' => 'string', 'group' => 'email', 'label' => 'E-mail de suporte (visível no site)'],
            ['key' => 'mail_from_address',   'value' => 'noreply@nexosn.pageup.net.br', 'type' => 'string', 'group' => 'email', 'label' => 'Remetente padrão dos e-mails'],
            ['key' => 'mail_from_name',      'value' => 'NEXOSN',                       'type' => 'string', 'group' => 'email', 'label' => 'Nome do remetente'],
            ['key' => 'app_url',             'value' => 'https://nexosn.pageup.net.br', 'type' => 'string', 'group' => 'email', 'label' => 'URL do sistema (usada em links de e-mail)'],

            // ── Integrações ───────────────────────────────────────────────────
            ['key' => 'efi_sandbox',         'value' => '1',      'type' => 'boolean', 'group' => 'integracoes', 'label' => 'Efi Bank: modo sandbox (homologação)'],

            // ── Legal ─────────────────────────────────────────────────────────
            ['key' => 'terms_version',       'value' => '1.0',    'type' => 'string',  'group' => 'legal',       'label' => 'Versão atual dos Termos de Uso'],
            ['key' => 'terms_updated_at',    'value' => '19 de julho de 2026', 'type' => 'string', 'group' => 'legal', 'label' => 'Data de atualização dos Termos'],
            ['key' => 'cookie_banner_text',  'value' => 'Usamos cookies essenciais para autenticação e sessão. Ao continuar, você concorda com nossa <a href="/legal/cookies">Política de Cookies</a>.', 'type' => 'string', 'group' => 'legal', 'label' => 'Texto do banner de cookies'],
        ];

        foreach ($settings as $data) {
            AppSetting::firstOrCreate(['key' => $data['key']], $data);
        }
    }
}
