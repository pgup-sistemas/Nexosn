<?php

namespace App\Filament\Resources\CardAppointmentResource\Pages;

use App\Filament\Resources\CardAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCardAppointments extends ManageRecords
{
    protected static string $resource = CardAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
