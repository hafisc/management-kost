<?php

namespace App\Filament\Resources\Rooms\Tables;

use App\Models\Room;
use App\Services\RoomService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('building.name')
                    ->label('Building')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('floor.name')
                    ->label('Floor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('occupancy_status')
                    ->label('Occupancy')
                    ->badge()
                    ->state(function (Room $record): string {
                        $roomService = app(RoomService::class);
                        $occupancy = $roomService->calculateOccupancy($record);

                        return ucfirst($occupancy['status']);
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Available' => 'success',
                        'Partial' => 'warning',
                        'Full' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('building_id')
                    ->relationship('building', 'name')
                    ->label('Building'),
                SelectFilter::make('floor_id')
                    ->relationship('floor', 'name')
                    ->label('Floor'),
                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
