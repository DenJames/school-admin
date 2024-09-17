<?php

namespace App\Filament\Resources\AssignedGradeResource\Pages;

use App\Filament\Resources\AssignedGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignedGrade extends EditRecord
{
    protected static string $resource = AssignedGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
