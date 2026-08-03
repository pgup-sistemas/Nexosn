<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CampaignEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'campaignEvents';
    protected static ?string $title = 'Agenda de Eventos (Campanha)';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Título')->required(),
            Forms\Components\DatePicker::make('event_date')->label('Data')->required(),
            Forms\Components\TimePicker::make('event_time')->label('Horário'),
            Forms\Components\TextInput::make('location')->label('Local'),
            Forms\Components\TextInput::make('map_url')->label('Link do mapa')->url(),
            Forms\Components\Textarea::make('description')->label('Descrição'),
            Forms\Components\Toggle::make('is_active')->label('Ativo')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('event_date')->label('Data')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('location')->label('Local'),
                Tables\Columns\IconColumn::make('is_active')->label('Ativo')->boolean(),
            ])
            ->defaultSort('event_date')
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
