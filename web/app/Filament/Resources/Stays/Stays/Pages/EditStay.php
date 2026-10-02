<?php

namespace App\Filament\Resources\Stays\Stays\Pages;

use App\Filament\Resources\Stays\Stays\StayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStay extends EditRecord
{
    protected static string $resource = StayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
