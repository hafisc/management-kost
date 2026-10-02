<?php

namespace App\Filament\Resources\Expenses\Expenses\Schemas;

use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengeluaran')
                    ->schema([
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'maintenance' => 'Perawatan',
                                'utilities' => 'Utilitas (Listrik/Air)',
                                'supplies' => 'Perlengkapan',
                                'repairs' => 'Perbaikan',
                                'cleaning' => 'Kebersihan',
                                'security' => 'Keamanan',
                                'tax' => 'Pajak',
                                'other' => 'Lainnya',
                            ])
                            ->required()
                            ->default('other'),

                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('amount')
                            ->label('Nominal')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->minValue(0),

                        DatePicker::make('expense_date')
                            ->label('Tanggal Pengeluaran')
                            ->required()
                            ->native(false)
                            ->default(now()),

                        Select::make('building_id')
                            ->label('Gedung (Opsional)')
                            ->relationship('building', 'name')
                            ->searchable()
                            ->preload(),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),

                        FileUpload::make('receipt')
                            ->label('Bukti/Struk')
                            ->image()
                            ->maxSize(5120)
                            ->directory('expense-receipts')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
