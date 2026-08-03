<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CampaignTimelineRelationManager extends RelationManager
{
    protected static string $relationship = 'campaignTimelineItems';
    protected static ?string $title = 'Linha do Tempo (Campanha)';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('occurred_on')->label('Data')->required(),
            Forms\Components\TextInput::make('title')->label('Título')->required(),
            Forms\Components\Textarea::make('description')->label('Descrição'),
            Forms\Components\TextInput::make('icon')->label('Ícone (Lucide, opcional)'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('occurred_on')->label('Data')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
            ])
            ->defaultSort('occurred_on')
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
