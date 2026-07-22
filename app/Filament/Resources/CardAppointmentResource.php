<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardAppointmentResource\Pages;
use App\Models\CardAppointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CardAppointmentResource extends Resource
{
    protected static ?string $model = CardAppointment::class;
    protected static ?string $navigationLabel = 'Agendamentos';
    protected static ?string $navigationGroup = 'Usuários e Cartões';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('schedule.card.user');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('visitor_name')->label('Visitante')->disabled(),
            Forms\Components\TextInput::make('visitor_email')->label('E-mail')->disabled(),
            Forms\Components\TextInput::make('visitor_phone')->label('Telefone')->disabled(),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending'   => 'Pendente',
                    'confirmed' => 'Confirmado',
                    'refused'   => 'Recusado',
                ])
                ->required(),
            Forms\Components\Textarea::make('notes')->label('Observações')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.card.user.name')
                    ->label('Titular')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visitor_name')
                    ->label('Visitante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Horário'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger'  => 'refused',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending'   => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'refused'   => 'Recusado',
                        default     => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Solicitado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('appointment_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'refused'   => 'Recusado',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCardAppointments::route('/'),
        ];
    }
}
