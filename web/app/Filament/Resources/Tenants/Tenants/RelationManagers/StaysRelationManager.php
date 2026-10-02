<?php

namespace App\Filament\Resources\Tenants\Tenants\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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
                    ->relationship('room', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                DatePicker::make('start_date')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('Tanggal Keluar (Opsional)')
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ->defaultSort('start_date', 'desc');
    }
}
