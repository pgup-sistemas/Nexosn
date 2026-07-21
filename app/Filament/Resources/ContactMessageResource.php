<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationLabel = 'Mensagens de Contato';
    protected static ?string $navigationGroup = 'Usuários e Cartões';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('card.user');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('sender_name')->label('Nome')->disabled(),
            Forms\Components\TextInput::make('sender_email')->label('E-mail')->disabled(),
            Forms\Components\TextInput::make('sender_phone')->label('Telefone')->disabled(),
            Forms\Components\Textarea::make('message')->label('Mensagem')->disabled()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('read_at')
                    ->label('Lida')
                    ->boolean()
                    ->getStateUsing(fn (ContactMessage $record) => !$record->isUnread()),
                Tables\Columns\TextColumn::make('card.user.name')
                    ->label('Cartão (titular)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Remetente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender_email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(60),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recebida em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Lida')
                    ->nullable()
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('read_at'),
                        false: fn ($query) => $query->whereNull('read_at'),
                    ),
            ])
            ->actions([
                Action::make('marcar_lida')
                    ->label('Marcar como lida')
                    ->icon('heroicon-o-check')
                    ->visible(fn (ContactMessage $record) => $record->isUnread())
                    ->action(fn (ContactMessage $record) => $record->update(['read_at' => now()])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContactMessages::route('/'),
        ];
    }
}
