<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\InvoicesRelationManager;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\PlanService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $navigationGroup = 'Usuários e Cartões';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $navigationIcon  = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados pessoais')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nome')->required(),
                Forms\Components\TextInput::make('email')->label('E-mail')->email()->required(),
                Forms\Components\TextInput::make('slug')->label('Slug do cartão'),
            ]),
            Forms\Components\Section::make('Plano')->columns(2)->schema([
                Forms\Components\Select::make('plan')
                    ->label('Plano')
                    ->options(['free' => 'Free', 'pro' => 'Pro'])
                    ->required(),
                Forms\Components\TextInput::make('efi_subscription_id')
                    ->label('ID da assinatura Efi')
                    ->disabled()
                    ->placeholder('(preenchido automaticamente)'),
                Forms\Components\DateTimePicker::make('plan_expires_at')
                    ->label('Pro expira em')
                    ->displayFormat('d/m/Y H:i'),
                Forms\Components\DateTimePicker::make('trial_ends_at')
                    ->label('Trial expira em')
                    ->displayFormat('d/m/Y H:i'),
            ]),
            Forms\Components\Section::make('Acesso')->schema([
                Forms\Components\Toggle::make('is_admin')
                    ->label('Acesso ao painel admin')
                    ->helperText('Concede acesso total ao /admin. Use com cuidado.')
                    ->default(false),
            ]),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('card');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('status_label')
                    ->label('Status')
                    ->getStateUsing(function (User $record): string {
                        if ($record->isPro() && !$record->isOnTrial()) return 'Pro Pago';
                        if ($record->isOnTrial())                       return 'Trial';
                        if ($record->trial_ends_at && $record->trial_ends_at->isPast() && $record->plan === 'free') return 'Trial expirado';
                        return 'Free';
                    })
                    ->colors([
                        'success' => 'Pro Pago',
                        'warning' => 'Trial',
                        'danger'  => 'Trial expirado',
                        'gray'    => 'Free',
                    ]),

                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Trial até')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('plan_expires_at')
                    ->label('Pro até')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('invoices_count')
                    ->label('Faturas')
                    ->counts('invoices')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('card.slug')
                    ->label('Cartão')
                    ->url(fn ($record) => $record->card ? url('/u/' . $record->card->slug) : null)
                    ->openUrlInNewTab()
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastro')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->label('Plano')
                    ->options(['free' => 'Free', 'pro' => 'Pro']),

                Filter::make('pro_pago')
                    ->label('Pro pago (não trial)')
                    ->query(fn ($q) => $q->where('plan', 'pro')
                        ->whereNotNull('plan_expires_at')
                        ->where('plan_expires_at', '>', now())),

                Filter::make('trial_ativo')
                    ->label('Trial ativo')
                    ->query(fn ($q) => $q->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '>', now())
                        ->whereNull('plan_expires_at')),

                Filter::make('trial_expirado')
                    ->label('Trial expirado sem conversão')
                    ->query(fn ($q) => $q->where('plan', 'free')
                        ->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '<', now())),

                TernaryFilter::make('is_admin')->label('Acesso admin'),
            ])
            ->actions([
                // ── Ativar Pro manualmente ──
                Action::make('ativar_pro')
                    ->label('Ativar Pro')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->visible(fn (User $r) => !$r->isPaid())
                    ->form([
                        Forms\Components\Select::make('plan_type')
                            ->label('Tipo de plano')
                            ->options(['monthly' => 'Mensal', 'annual' => 'Anual'])
                            ->default('monthly')
                            ->required(),
                        Forms\Components\TextInput::make('months')
                            ->label('Duração (meses)')
                            ->numeric()->default(1)->minValue(1)->maxValue(24)->required(),
                        Forms\Components\Textarea::make('note')
                            ->label('Observação interna')
                            ->rows(2)->placeholder('Ex: cortesia, parceria, etc.'),
                    ])
                    ->action(function (User $record, array $data) {
                        app(PlanService::class)->adminActivatePro(
                            $record,
                            $data['plan_type'],
                            (int) $data['months'],
                            $data['note'] ?? null
                        );
                        Notification::make()->title('Pro ativado com sucesso!')->success()->send();
                    }),

                // ── Estender trial ──
                Action::make('estender_trial')
                    ->label('Estender trial')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->visible(fn (User $r) => $r->isOnTrial())
                    ->form([
                        Forms\Components\TextInput::make('days')
                            ->label('Dias adicionais')
                            ->numeric()->default(7)->minValue(1)->maxValue(90)->required(),
                        Forms\Components\Textarea::make('note')
                            ->label('Motivo')
                            ->rows(2),
                    ])
                    ->action(function (User $record, array $data) {
                        app(PlanService::class)->adminExtendTrial(
                            $record,
                            (int) $data['days'],
                            $data['note'] ?? null
                        );
                        Notification::make()->title('Trial estendido!')->success()->send();
                    }),

                // ── Cancelar para Free ──
                Action::make('cancelar_pro')
                    ->label('Cancelar para Free')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar assinatura Pro?')
                    ->modalDescription('O usuário será rebaixado para o plano Free imediatamente.')
                    ->visible(fn (User $r) => $r->isPro())
                    ->form([
                        Forms\Components\Textarea::make('note')
                            ->label('Motivo do cancelamento')
                            ->rows(2),
                    ])
                    ->action(function (User $record, array $data) {
                        app(PlanService::class)->adminDowngradeToFree(
                            $record,
                            $data['note'] ?? null
                        );
                        Notification::make()->title('Plano cancelado para Free.')->warning()->send();
                    }),

                // ── Impersonar ──
                Action::make('impersonar')
                    ->label('Acessar como')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->id !== Auth::id())
                    ->action(function (User $record) {
                        AuditLog::create([
                            'action'     => 'impersonation',
                            'admin_id'   => Auth::id(),
                            'target_id'  => $record->id,
                            'ip_address' => request()->ip(),
                        ]);
                        session(['impersonator_id' => Auth::id()]);
                        Auth::loginUsingId($record->id);
                        return redirect('/dashboard');
                    }),

                // ── Suspender / reativar cartão ──
                Action::make('suspender_cartao')
                    ->label('Suspender cartão')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (User $r) => (bool) $r->card?->is_active)
                    ->action(function (User $r) {
                        $r->card?->update(['is_active' => false]);
                        AuditLog::create([
                            'action'     => 'card_suspended',
                            'admin_id'   => Auth::id(),
                            'target_id'  => $r->id,
                            'ip_address' => request()->ip(),
                        ]);
                        Notification::make()->title('Cartão suspenso.')->warning()->send();
                    }),

                Action::make('reativar_cartao')
                    ->label('Reativar cartão')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $r) => !($r->card?->is_active))
                    ->action(function (User $r) {
                        $r->card?->update(['is_active' => true]);
                        AuditLog::create([
                            'action'     => 'card_reactivated',
                            'admin_id'   => Auth::id(),
                            'target_id'  => $r->id,
                            'ip_address' => request()->ip(),
                        ]);
                        Notification::make()->title('Cartão reativado.')->success()->send();
                    }),

                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Exportar CSV')
                        ->icon('heroicon-o-arrow-down-tray'),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            InvoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
