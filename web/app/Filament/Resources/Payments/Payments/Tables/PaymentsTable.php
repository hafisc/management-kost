<?php

namespace App\Filament\Resources\Payments\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_date')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tenant.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('period_start')
                    ->label('Periode')
                    ->formatStateUsing(fn ($record) => $record->period_start->format('d M Y').' - '.$record->period_end->format('d M Y'))
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->colors([
                        'success' => 'cash',
                        'info' => 'transfer',
                        'warning' => 'e_wallet',
                        'gray' => 'other',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'cash' => 'Tunai',
                        'transfer' => 'Transfer',
                        'e_wallet' => 'E-Wallet',
                        'other' => 'Lainnya',
                        default => $state,
                    }),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Metode')
                    ->options([
                        'cash' => 'Tunai',
                        'transfer' => 'Transfer',
                        'e_wallet' => 'E-Wallet',
                        'other' => 'Lainnya',
                    ]),

                SelectFilter::make('tenant_id')
                    ->label('Penyewa')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('this_month')
                    ->label('Bulan Ini')
                    ->queries(
                        true: fn ($query) => $query->whereMonth('payment_date', now()->month)
                            ->whereYear('payment_date', now()->year),
                        false: fn ($query) => $query->where(function ($q) {
                            $q->whereMonth('payment_date', '!=', now()->month)
                                ->orWhereYear('payment_date', '!=', now()->year);
                        }),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('payment_date', 'desc');
    }
}
