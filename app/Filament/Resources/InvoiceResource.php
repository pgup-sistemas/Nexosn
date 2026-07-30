<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationLabel = 'Faturas';
    protected static ?string $navigationGroup = 'Financeiro';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';

    public static function getNavigationBadge(): ?string
    {
        $count = Invoice::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('plan_type')
                    ->label('Tipo de plano')
                    ->options(['monthly' => 'Mensal', 'annual' => 'Anual'])
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Valor (R$)')
                    ->numeric()->step(0.01)->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'paid'     => 'Pago',
                        'pending'  => 'Pendente',
                        'failed'   => 'Falhou',
                        'refunded' => 'Estornado',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('efi_subscription_id')
                    ->label('ID Assinatura Efi')
                    ->placeholder('(automático)'),

                Forms\Components\TextInput::make('efi_charge_id')
                    ->label('ID Cobrança Efi')
                    ->placeholder('(automático)'),

                Forms\Components\TextInput::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('paid_at')->label('Pago em'),
                Forms\Components\DateTimePicker::make('due_at')->label('Vencimento'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger'  => 'failed',
                        'gray'    => 'refunded',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'paid'     => 'Pago',
                        'pending'  => 'Pendente',
                        'failed'   => 'Falhou',
                        'refunded' => 'Estornado',
                        default    => $state,
                    }),

                Tables\Columns\BadgeColumn::make('plan_type')
                    ->label('Plano')
                    ->colors(['primary' => 'monthly', 'success' => 'annual'])
                    ->formatStateUsing(fn ($state) => $state === 'annual' ? 'Anual' : 'Mensal'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format((float) $state, 2, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(35)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('efi_subscription_id')
                    ->label('ID Efi')
                    ->placeholder('—')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Pago em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'paid'     => 'Pago',
                        'pending'  => 'Pendente',
                        'failed'   => 'Falhou',
                        'refunded' => 'Estornado',
                    ]),
                SelectFilter::make('plan_type')
                    ->label('Tipo')
                    ->options(['monthly' => 'Mensal', 'annual' => 'Anual']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()->label('Exportar CSV'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit'   => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
