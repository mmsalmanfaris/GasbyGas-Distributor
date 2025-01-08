<?php

namespace App\Filament\Outlet\Resources\DispatchSchedulesResource\Pages;

use App\Filament\Outlet\Resources\DispatchSchedulesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispatchSchedules extends ListRecords
{
    protected static string $resource = DispatchSchedulesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
