<?php

namespace App\Filament\Resources\Floors;

use App\Filament\Resources\Floors\Pages\CreateFloor;
use App\Filament\Resources\Floors\Pages\EditFloor;
use App\Filament\Resources\Floors\Pages\ListFloors;
use App\Filament\Resources\Floors\Schemas\FloorForm;
use App\Filament\Resources\Floors\Tables\FloorsTable;
use App\Models\Floor;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FloorResource extends Resource
{
    protected static ?string $model = Floor::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-queue-list';

    protected static \UnitEnum|string|null $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Lantai';

    protected static ?string $modelLabel = 'Lantai';

    protected static ?string $pluralModelLabel = 'Lantai';

    public static function form(Schema $schema): Schema
    {
        return FloorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FloorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFloors::route('/'),
            'create' => CreateFloor::route('/create'),
            'edit' => EditFloor::route('/{record}/edit'),
        ];
    }
}
