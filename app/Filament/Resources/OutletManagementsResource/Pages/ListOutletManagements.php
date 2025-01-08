<?php

namespace App\Filament\Resources\OutletManagementsResource\Pages;

use App\Filament\Resources\OutletManagementsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutletManagements extends ListRecords
{
    protected static string $resource = OutletManagementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
