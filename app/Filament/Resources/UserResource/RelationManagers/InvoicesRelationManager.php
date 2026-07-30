<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';
    protected static ?string $title = 'Faturas';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('plan_type')
                ->label('Tipo')
                ->options(['monthly' => 'Mensal', 'annual' => 'Anual'])
                ->required(),
            Forms\Components\TextInput::make('amount')
                ->label('Valor (R$)')
                ->numeric()->required(),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'paid'     => 'Pago',
                    'pending'  => 'Pendente',
                    'failed'   => 'Falhou',
                    'refunded' => 'Estornado',
                ])
                ->required(),
            Forms\Components\TextInput::make('description')->label('Descrição'),
            Forms\Components\DateTimePicker::make('paid_at')->label('Pago em'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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

                Tables\Columns\TextColumn::make('plan_type')
                    ->label('Plano')
                    ->formatStateUsing(fn ($state) => $state === 'annual' ? 'Anual' : 'Mensal'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format((float) $state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('description')->label('Descrição')->limit(40),

                Tables\Columns\TextColumn::make('efi_subscription_id')
                    ->label('ID Efi')
                    ->placeholder('—')
                    ->copyable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Pago em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Lançar fatura manual'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
