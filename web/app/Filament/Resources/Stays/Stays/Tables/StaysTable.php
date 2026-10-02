<?php

namespace App\Filament\Resources\Stays\Stays\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.name')
                    ->label('Kamar')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.building.name')
                    ->label('Gedung')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Keluar')
                    ->date('d M Y')
                    ->placeholder('Masih menghuni')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'ended',
                    ])
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->state(fn ($record) => $record->end_date
                        ? $record->start_date->diffInDays($record->end_date).' hari'
                        : $record->start_date->diffInDays(now()).' hari'
                    ),
            ])
            ->filters([
                SelectFilter::make('room_id')
                    ->label('Kamar')
                    ->relationship('room', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'ended' => 'Selesai',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
    }
}
