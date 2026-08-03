<?php

namespace App\Filament\Resources\CardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CardFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';
    protected static ?string $title = 'Arquivos (Plano de Gestão / Materiais)';
    protected static ?string $recordTitleAttribute = 'label';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')->label('Nome')->required(),
            Forms\Components\Select::make('category')->label('Categoria')->options([
                'management_plan' => 'Plano de Gestão',
                'material' => 'Material',
                'other' => 'Outro',
            ])->default('material')->required(),
            Forms\Components\TextInput::make('order')->label('Ordem')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('category')->label('Categoria'),
                Tables\Columns\TextColumn::make('order')->label('Ordem')->sortable(),
            ])
            ->defaultSort('order')
            // Sem CreateAction: o upload real (com validação de MIME) só acontece
            // pelo painel do titular (FileManager). Admin só edita metadados/exclui.
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }
}
