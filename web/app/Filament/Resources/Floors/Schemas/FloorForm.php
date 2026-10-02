<?php

namespace App\Filament\Resources\Floors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FloorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('building_id')
                    ->relationship('building', 'name')
                    ->required(),
                TextInput::make('floor_number')
                    ->required()
                    ->numeric(),
                TextInput::make('name'),
            ]);
    }
}
