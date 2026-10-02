<?php

namespace App\Filament\Resources\Stays\Stays\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.full_name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.building.name')
                    ->label('Gedung')
                    ->sortable(),

                TextColumn::make('start_at')
                    ->label('Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_at')
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
                    ->state(fn ($record) => $record->end_at
                        ? $record->start_at->diffInDays($record->end_at).' hari'
                        : $record->start_at->diffInDays(now()).' hari'
                    ),
            ])
            ->filters([
                SelectFilter::make('room_id')
                    ->label('Kamar')
                    ->relationship('room', 'room_number')
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
            ->defaultSort('start_at', 'desc');
    }
}
