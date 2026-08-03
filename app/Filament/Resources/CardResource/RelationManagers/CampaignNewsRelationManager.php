<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CampaignNewsRelationManager extends RelationManager
{
    protected static string $relationship = 'campaignNews';
    protected static ?string $title = 'Notícias (Campanha)';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Título')->required(),
            Forms\Components\Textarea::make('body')->label('Texto'),
            Forms\Components\DateTimePicker::make('published_at')->label('Publicada em'),
            Forms\Components\Toggle::make('is_active')->label('Publicada')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('published_at')->label('Publicada em')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Ativa')->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }
}
