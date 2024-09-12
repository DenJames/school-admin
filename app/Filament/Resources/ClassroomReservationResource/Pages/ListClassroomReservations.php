<?php

namespace App\Filament\Resources\ClassroomReservationResource\Pages;

use App\Filament\Resources\ClassroomReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomReservations extends ListRecords
{
    protected static string $resource = ClassroomReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
