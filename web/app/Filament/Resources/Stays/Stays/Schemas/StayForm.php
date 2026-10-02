<?php

namespace App\Filament\Resources\Stays\Stays\Schemas;

use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Schema;

class StayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Menginap')
                    ->schema([
                        Select::make('tenant_id')
                            ->label('Penyewa')
                            ->relationship('tenant', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('phone')
                                    ->required()
                                    ->maxLength(20),
                            ]),

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
                            ->native(false)
                            ->afterOrEqual('start_date'),
                    ]),
            ]);
    }
}
