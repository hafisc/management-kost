<?php

namespace App\Filament\Resources\Expenses\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expense_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'danger' => 'repairs',
                        'warning' => 'maintenance',
                        'info' => 'utilities',
                        'success' => 'cleaning',
                        'gray' => 'other',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'maintenance' => 'Perawatan',
                        'utilities' => 'Utilitas',
                        'supplies' => 'Perlengkapan',
                        'repairs' => 'Perbaikan',
                        'cleaning' => 'Kebersihan',
                        'security' => 'Keamanan',
                        'tax' => 'Pajak',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('building.name')
                    ->label('Gedung')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                ImageColumn::make('receipt')
                    ->label('Bukti')
                    ->size(40)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'maintenance' => 'Perawatan',
                        'utilities' => 'Utilitas',
                        'supplies' => 'Perlengkapan',
                        'repairs' => 'Perbaikan',
                        'cleaning' => 'Kebersihan',
                        'security' => 'Keamanan',
                        'tax' => 'Pajak',
                        'other' => 'Lainnya',
                    ]),

                SelectFilter::make('building_id')
                    ->label('Gedung')
                    ->relationship('building', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('this_month')
                    ->label('Bulan Ini')
                    ->queries(
                        true: fn ($query) => $query->whereMonth('expense_date', now()->month)
                            ->whereYear('expense_date', now()->year),
                        false: fn ($query) => $query->where(function ($q) {
                            $q->whereMonth('expense_date', '!=', now()->month)
                                ->orWhereYear('expense_date', '!=', now()->year);
                        }),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('expense_date', 'desc');
    }
}
