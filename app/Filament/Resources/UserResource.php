<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\AuditLog;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
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
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nome')->required(),
            Forms\Components\TextInput::make('email')->label('E-mail')->email()->required(),
            Forms\Components\TextInput::make('slug')->label('Slug'),
            Forms\Components\Select::make('plan')
                ->label('Plano')
                ->options(['free' => 'Free', 'pro' => 'Pro'])
                ->required(),
            Forms\Components\DateTimePicker::make('plan_expires_at')->label('Pro expira em'),
            Forms\Components\DateTimePicker::make('trial_ends_at')->label('Trial expira em'),
            Forms\Components\Toggle::make('is_admin')
                ->label('Acesso ao painel admin')
                ->helperText('Concede acesso ao /admin. Cuidado ao conceder para outros usuários.')
                ->default(false),
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
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('plan')
                    ->label('Plano')
                    ->colors(['warning' => 'free', 'success' => 'pro']),
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
                Tables\Columns\TextColumn::make('card.slug')
                    ->label('Slug')
                    ->url(fn ($record) => $record->card ? url('/u/' . $record->card->slug) : null)
                    ->openUrlInNewTab(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->label('Plano')
                    ->options(['free' => 'Free', 'pro' => 'Pro']),
                Filter::make('trial_ativo')
                    ->label('Trial ativo')
                    ->query(fn ($query) => $query->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now())),
                TernaryFilter::make('is_admin')
                    ->label('Acesso admin'),
            ])
            ->actions([
                Action::make('impersonar')
                    ->label('Acessar como')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('warning')
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
                Action::make('suspender_cartao')
                    ->label('Suspender cartão')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => (bool) $record->card?->is_active)
                    ->action(fn (User $record) => $record->card?->update(['is_active' => false])),
                Action::make('reativar_cartao')
                    ->label('Reativar cartão')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => !($record->card?->is_active))
                    ->action(fn (User $record) => $record->card?->update(['is_active' => true])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
