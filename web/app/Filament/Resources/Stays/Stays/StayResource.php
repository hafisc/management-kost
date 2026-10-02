<?php

namespace App\Filament\Resources\Stays\Stays;

use App\Filament\Resources\Stays\Stays\Pages\CreateStay;
use App\Filament\Resources\Stays\Stays\Pages\EditStay;
use App\Filament\Resources\Stays\Stays\Pages\ListStays;
use App\Filament\Resources\Stays\Stays\Schemas\StayForm;
use App\Filament\Resources\Stays\Stays\Tables\StaysTable;
use App\Models\Stays\Stay;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StayResource extends Resource
{
    protected static ?string $model = Stay::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static \UnitEnum|string|null $navigationGroup = 'Management';

    public static function form(Schema $schema): Schema
    {
        return StayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaysTable::configure($table);
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
            'index' => ListStays::route('/'),
            'create' => CreateStay::route('/create'),
            'edit' => EditStay::route('/{record}/edit'),
        ];
    }
}
