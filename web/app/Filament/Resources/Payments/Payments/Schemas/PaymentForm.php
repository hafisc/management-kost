<?php

namespace App\Filament\Resources\Payments\Payments\Schemas;

use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Informasi Pembayaran')
                    ->columnSpan(2)
                    ->schema([
                        Select::make('tenant_id')
                            ->label('Penyewa')
                            ->relationship('tenant', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        DatePicker::make('period_start')
                            ->label('Periode Dari')
                            ->required()
                            ->native(false),

                        DatePicker::make('period_end')
                            ->label('Periode Sampai')
                            ->required()
                            ->native(false)
                            ->afterOrEqual('period_start'),

                        DatePicker::make('payment_date')
                            ->label('Tanggal Bayar')
                            ->required()
                            ->native(false)
                            ->default(now()),

                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Tunai',
                                'transfer' => 'Transfer Bank',
                                'e_wallet' => 'E-Wallet',
                                'other' => 'Lainnya',
                            ])
                            ->required()
                            ->default('cash'),

                        TextInput::make('amount')
                            ->label('Total Nominal')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->minValue(0),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Rincian Item')
                    ->columnSpan(1)
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->placeholder('Sewa kamar, listrik, dll'),

                                TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0),

                                TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Item')
                            ->collapsible()
                            ->cloneable(),
                    ]),
            ]);
    }
}
