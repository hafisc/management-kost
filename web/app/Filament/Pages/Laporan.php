<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\Payment;
use Filament\Pages\Page;

class Laporan extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan Keuangan';

    protected string $view = 'filament.pages.laporan';

    public $selectedMonth;

    public $selectedYear;

    public $selectedBuilding = 'all';

    public function mount(): void
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
    }

    public function getRevenue(): float
    {
        return Payment::whereMonth('payment_date', $this->selectedMonth)
            ->whereYear('payment_date', $this->selectedYear)
            ->sum('amount');
    }

    public function getExpenses(): float
    {
        $query = Expense::whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear);

        if ($this->selectedBuilding !== 'all') {
            $query->where('building_id', $this->selectedBuilding);
        }

        return $query->sum('amount');
    }

    public function getNetIncome(): float
    {
        return $this->getRevenue() - $this->getExpenses();
    }

    public function getRevenueDetails(): array
    {
        $payments = Payment::whereMonth('payment_date', $this->selectedMonth)
            ->whereYear('payment_date', $this->selectedYear)
            ->with('tenant')
            ->get();

        return [
            'total' => $payments->sum('amount'),
            'count' => $payments->count(),
            'breakdown' => $payments->groupBy('payment_method')->map(fn ($items) => [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ]),
        ];
    }

    public function getExpenseDetails(): array
    {
        $query = Expense::whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear);

        if ($this->selectedBuilding !== 'all') {
            $query->where('building_id', $this->selectedBuilding);
        }

        $expenses = $query->get();

        return [
            'total' => $expenses->sum('amount'),
            'count' => $expenses->count(),
            'breakdown' => $expenses->groupBy('category')->map(fn ($items) => [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ]),
        ];
    }

    public function exportExcel()
    {
        // TODO: Implement Excel export
        $this->notify('success', 'Export Excel akan segera tersedia');
    }
}
