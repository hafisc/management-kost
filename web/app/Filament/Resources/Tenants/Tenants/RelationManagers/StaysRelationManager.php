<?php

namespace App\Filament\Resources\Tenants\Tenants\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaysRelationManager extends RelationManager
{
    protected static string $relationship = 'stays';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_id')
                    ->label('Kamar')
                    ->relationship('room', 'room_number')
                    ->required()
                    ->searchable()
                    ->preload(),

                DatePicker::make('start_at')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->native(false),

                DatePicker::make('end_at')
                    ->label('Tanggal Keluar (Opsional)')
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->sortable()
                    ->placeholder('Masih menghuni'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'ended',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_at', 'desc');
    }
}
