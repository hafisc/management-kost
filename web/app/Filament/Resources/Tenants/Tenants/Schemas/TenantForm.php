<?php

namespace App\Filament\Resources\Tenants\Tenants\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Penyewa')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->regex('/^(\+?62|0)8[1-9][0-9]{6,11}$/')
                            ->validationMessages([
                                'regex' => 'Format nomor WhatsApp tidak valid. Harus dimulai dengan 08 atau +628.',
                            ])
                            ->helperText('Format: 08xxxxxxxxxx atau +628xxxxxxxxxx'),
                    ]),
            ]);
    }
}
