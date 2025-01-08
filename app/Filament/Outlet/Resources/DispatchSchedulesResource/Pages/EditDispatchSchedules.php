<?php

namespace App\Filament\Outlet\Resources\DispatchSchedulesResource\Pages;

use App\Filament\Outlet\Resources\DispatchSchedulesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispatchSchedules extends EditRecord
{
    protected static string $resource = DispatchSchedulesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
