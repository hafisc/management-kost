<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header dengan Filter --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">
                    {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->locale('id')->format('F Y') }}
                </h2>
            </div>
            <div class="flex gap-4">
                <select wire:model.live="selectedMonth" class="rounded-lg border-gray-300">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->locale('id')->format('F') }}</option>
                    @endfor
                </select>
                <select wire:model.live="selectedYear" class="rounded-lg border-gray-300">
                    @for($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Pemasukan --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-2">Pemasukan</div>
                <div class="text-3xl font-bold text-green-600">
                    Rp {{ number_format($this->getRevenue(), 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-2">
                    {{ $this->getRevenueDetails()['count'] }} pembayaran
                </div>
            </div>

            {{-- Pengeluaran --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-2">Pengeluaran</div>
                <div class="text-3xl font-bold text-red-600">
                    Rp {{ number_format($this->getExpenses(), 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-2">
                    {{ $this->getExpenseDetails()['count'] }} transaksi
                </div>
            </div>

            {{-- Pendapatan Bersih --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-2">Pendapatan Bersih</div>
                <div class="text-3xl font-bold {{ $this->getNetIncome() >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                    Rp {{ number_format($this->getNetIncome(), 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-2">
                    {{ $this->getNetIncome() >= 0 ? 'Surplus' : 'Defisit' }}
                </div>
            </div>
        </div>

        {{-- Detail Breakdown --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Pemasukan Detail --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Detail Pemasukan</h3>
                <div class="space-y-3">
                    @foreach($this->getRevenueDetails()['breakdown'] as $method => $data)
                        <div class="flex justify-between items-center">
                            <span class="text-sm capitalize">{{ str_replace('_', ' ', $method) }}</span>
                            <div class="text-right">
                                <div class="font-semibold">Rp {{ number_format($data['total'], 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ $data['count'] }} transaksi</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pengeluaran Detail --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Detail Pengeluaran</h3>
                <div class="space-y-3">
                    @foreach($this->getExpenseDetails()['breakdown'] as $category => $data)
                        <div class="flex justify-between items-center">
                            <span class="text-sm capitalize">{{ $category }}</span>
                            <div class="text-right">
                                <div class="font-semibold">Rp {{ number_format($data['total'], 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ $data['count'] }} transaksi</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Export Button --}}
        <div class="flex justify-end">
            <button 
                wire:click="exportExcel"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
        </div>
    </div>
</x-filament-panels::page>
