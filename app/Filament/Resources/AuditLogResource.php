<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;
    protected static ?string $navigationLabel = 'Log de Auditoria';
    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['admin', 'target']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('action')->label('Ação')->disabled(),
            Forms\Components\TextInput::make('admin.name')->label('Admin')->disabled(),
            Forms\Components\TextInput::make('target.name')->label('Alvo')->disabled(),
            Forms\Components\TextInput::make('ip_address')->label('IP')->disabled(),
            Forms\Components\Textarea::make('payload')->label('Detalhes')->disabled()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->label('Ação')
                    ->badge(),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Admin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target.name')
                    ->label('Alvo')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAuditLogs::route('/'),
        ];
    }
}
