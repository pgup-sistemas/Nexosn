<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'photos';
    protected static ?string $title = 'Galeria de Fotos';
    protected static ?string $recordTitleAttribute = 'caption';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('caption')->label('Legenda'),
            Forms\Components\TextInput::make('order')->label('Ordem')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('caption')
            ->columns([
                Tables\Columns\ImageColumn::make('url')->label('Foto'),
                Tables\Columns\TextColumn::make('caption')->label('Legenda')->placeholder('—'),
                Tables\Columns\TextColumn::make('order')->label('Ordem')->sortable(),
            ])
            ->defaultSort('order')
            ->headerActions([])
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
