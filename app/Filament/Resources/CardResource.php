<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Filament\Resources\CardResource\RelationManagers\LinksRelationManager;
use App\Filament\Resources\CardResource\RelationManagers\PhotosRelationManager;
use App\Filament\Resources\CardResource\RelationManagers\ServicesRelationManager;
use App\Models\Card;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;
    protected static ?string $navigationLabel = 'Cartões';
    protected static ?string $navigationGroup = 'Usuários e Cartões';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Titular')
                ->relationship('user', 'name')
                ->required(),
            Forms\Components\TextInput::make('slug')->required(),
            Forms\Components\TextInput::make('display_name')->label('Nome no cartão')->required(),
            Forms\Components\Toggle::make('is_active')->label('Ativo')->default(true),
            Forms\Components\Toggle::make('show_watermark')->label('Mostrar marca d\'água')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Titular')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->url(fn ($record) => url('/u/' . $record->slug))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Nome no cartão')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Visualizações')
                    ->counts('views')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Ativo'),
            ])
            ->actions([
                Action::make('suspender')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => (bool) $record->is_active)
                    ->action(fn ($record) => $record->update(['is_active' => false])),
                Action::make('reativar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !(bool) $record->is_active)
                    ->action(fn ($record) => $record->update(['is_active' => true])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function getRelations(): array
    {
        return [
            LinksRelationManager::class,
            PhotosRelationManager::class,
            ServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit'   => Pages\EditCard::route('/{record}/edit'),
        ];
    }
}
