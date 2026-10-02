<?php

namespace App\Filament\Resources\Stays\Stays\Schemas;

use Filament\Forms\Components\TextInput;
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
                            ->relationship('tenant', 'full_name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('full_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('whatsapp_number')
                                    ->required()
                                    ->maxLength(20),
                            ]),

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
                            ->native(false)
                            ->afterOrEqual('start_at'),
                    ]),
            ]);
    }
}
