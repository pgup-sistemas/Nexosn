<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CampaignProposalsRelationManager extends RelationManager
{
    protected static string $relationship = 'campaignProposals';
    protected static ?string $title = 'Propostas (Campanha)';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Título')->required(),
            Forms\Components\Textarea::make('description')->label('Descrição'),
            Forms\Components\TextInput::make('video_url')->label('Vídeo (YouTube/Vimeo)')->url(),
            Forms\Components\TextInput::make('order')->label('Ordem')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('Ativa')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Categoria'),
                Tables\Columns\IconColumn::make('is_active')->label('Ativa')->boolean(),
                Tables\Columns\TextColumn::make('order')->label('Ordem')->sortable(),
            ])
            ->defaultSort('order')
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
