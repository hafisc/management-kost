<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Stay;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRooms = Room::where('is_active', true)->count();
        $occupiedRooms = Stay::active()->distinct('room_id')->count('room_id');
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $monthlyIncome = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $monthlyExpense = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        return [
            Stat::make('Total Kamar Tersedia', $totalRooms)
                ->description('Kamar aktif')
                ->descriptionIcon('heroicon-o-home')
                ->color('info'),

            Stat::make('Kamar Terisi', $occupiedRooms)
                ->description("{$occupancyRate}% occupancy")
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Penyewa Aktif', Stay::active()->distinct('tenant_id')->count('tenant_id'))
                ->description('Total: '.Tenant::count().' penyewa')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning'),

            Stat::make('Pendapatan Bulan Ini', 'Rp '.number_format($monthlyIncome, 0, ',', '.'))
                ->description('Pengeluaran: Rp '.number_format($monthlyExpense, 0, ',', '.'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($monthlyIncome > $monthlyExpense ? 'success' : 'danger'),
        ];
    }
}
