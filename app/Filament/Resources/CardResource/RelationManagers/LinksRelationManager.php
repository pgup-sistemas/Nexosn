<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LinksRelationManager extends RelationManager
{
    protected static string $relationship = 'links';
    protected static ?string $title = 'Links';
    protected static ?string $recordTitleAttribute = 'label';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')->label('Rótulo')->required(),
            Forms\Components\TextInput::make('url')->label('URL')->url()->required(),
            Forms\Components\TextInput::make('icon')->label('Ícone (Lucide, opcional)'),
            Forms\Components\TextInput::make('order')->label('Ordem')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('Ativo')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label')->label('Rótulo')->searchable(),
                Tables\Columns\TextColumn::make('url')->label('URL')->limit(40),
                Tables\Columns\IconColumn::make('is_active')->label('Ativo')->boolean(),
                Tables\Columns\TextColumn::make('click_count')->label('Cliques')->sortable(),
                Tables\Columns\TextColumn::make('order')->label('Ordem')->sortable(),
            ])
            ->defaultSort('order')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
