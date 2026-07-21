<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class Configuracoes extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configurações';
    protected static ?string $title           = 'Configurações do Sistema';
    protected static ?int    $navigationSort  = 99;
    protected static string  $view            = 'filament.pages.configuracoes';

    // ── Estado do formulário ─────────────────────────────────────────────────

    public float  $plan_price_monthly  = 29.90;
    public int    $trial_days          = 14;
    public int    $limit_free_links    = 5;
    public int    $limit_free_photos   = 3;
    public int    $limit_free_services = 3;
    public bool   $maintenance_mode    = false;

    public string $support_email       = '';
    public string $mail_from_address   = '';
    public string $mail_from_name      = '';
    public string $app_url             = '';

    public bool   $efi_sandbox         = true;

    public string $terms_version       = '';
    public string $terms_updated_at    = '';
    public string $cookie_banner_text  = '';

    // ── Boot ─────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $keys = [
            'plan_price_monthly', 'trial_days', 'limit_free_links',
            'limit_free_photos', 'limit_free_services', 'maintenance_mode',
            'support_email', 'mail_from_address', 'mail_from_name', 'app_url',
            'efi_sandbox', 'terms_version', 'terms_updated_at', 'cookie_banner_text',
        ];

        foreach ($keys as $key) {
            $default = $this->{$key};
            $this->{$key} = AppSetting::get($key, $default);
        }

        $this->form->fill(array_combine($keys, array_map(fn ($k) => $this->{$k}, $keys)));
    }

    // ── Formulário ────────────────────────────────────────────────────────────

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── Negócio ─────────────────────────────────────────────────
                Section::make('Negócio')
                    ->description('Parâmetros de plano, limites e operação')
                    ->icon('heroicon-o-building-storefront')
                    ->columns(2)
                    ->schema([
                        TextInput::make('plan_price_monthly')
                            ->label('Preço Pro mensal (R$)')
                            ->numeric()->step(0.01)->minValue(0)->required(),

                        TextInput::make('trial_days')
                            ->label('Dias de trial gratuito')
                            ->numeric()->minValue(0)->maxValue(90)->required(),

                        TextInput::make('limit_free_links')
                            ->label('Limite de links (Free)')
                            ->numeric()->minValue(1)->required(),

                        TextInput::make('limit_free_photos')
                            ->label('Limite de fotos (Free)')
                            ->numeric()->minValue(1)->required(),

                        TextInput::make('limit_free_services')
                            ->label('Limite de serviços (Free)')
                            ->numeric()->minValue(1)->required(),

                        Toggle::make('maintenance_mode')
                            ->label('Modo manutenção')
                            ->helperText('Bloqueia acesso público ao sistema')
                            ->columnSpanFull(),
                    ]),

                // ── E-mail ───────────────────────────────────────────────────
                Section::make('E-mail e Contato')
                    ->description('Configurações de remetente e URLs usadas nos e-mails transacionais')
                    ->icon('heroicon-o-envelope')
                    ->columns(2)
                    ->schema([
                        TextInput::make('support_email')
                            ->label('E-mail de suporte (visível no site)')
                            ->email()->required(),

                        TextInput::make('mail_from_address')
                            ->label('Remetente padrão')
                            ->email()->required(),

                        TextInput::make('mail_from_name')
                            ->label('Nome do remetente')
                            ->required(),

                        TextInput::make('app_url')
                            ->label('URL do sistema')
                            ->url()->required()
                            ->helperText('Usada para gerar links nos e-mails'),
                    ]),

                // ── Integrações ───────────────────────────────────────────────
                Section::make('Integrações')
                    ->description('Credenciais ficam no .env — aqui apenas parâmetros de comportamento')
                    ->icon('heroicon-o-puzzle-piece')
                    ->schema([
                        Toggle::make('efi_sandbox')
                            ->label('Efi Bank: modo sandbox (homologação)')
                            ->helperText('Desative somente em produção com credenciais reais'),
                    ]),

                // ── Legal ─────────────────────────────────────────────────────
                Section::make('Textos Legais')
                    ->description('Versões e textos exibidos no site')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        TextInput::make('terms_version')
                            ->label('Versão dos Termos de Uso')
                            ->placeholder('1.0'),

                        TextInput::make('terms_updated_at')
                            ->label('Data de atualização dos Termos')
                            ->placeholder('19 de julho de 2026'),

                        Textarea::make('cookie_banner_text')
                            ->label('Texto do banner de cookies')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('HTML permitido: <a href="...">, <strong>'),
                    ]),
            ])
            ->statePath('data');
    }

    public array $data = [];

    // ── Salvar ────────────────────────────────────────────────────────────────

    public function save(): void
    {
        $state = $this->form->getState();

        $types = [
            'plan_price_monthly'  => 'float',
            'trial_days'          => 'integer',
            'limit_free_links'    => 'integer',
            'limit_free_photos'   => 'integer',
            'limit_free_services' => 'integer',
            'maintenance_mode'    => 'boolean',
            'efi_sandbox'         => 'boolean',
        ];

        foreach ($state as $key => $value) {
            $row = \App\Models\AppSetting::where('key', $key)->firstOrNew(['key' => $key]);
            $row->value = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
            $row->type  = $types[$key] ?? 'string';
            $row->save();
            Cache::forget("app_setting:{$key}");
        }

        Notification::make()
            ->title('Configurações salvas com sucesso.')
            ->success()
            ->send();
    }
}
