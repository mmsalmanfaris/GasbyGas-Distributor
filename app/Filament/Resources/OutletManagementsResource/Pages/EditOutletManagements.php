<?php

namespace App\Filament\Resources\OutletManagementsResource\Pages;

use App\Filament\Resources\OutletManagementsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutletManagements extends EditRecord
{
    protected static string $resource = OutletManagementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
