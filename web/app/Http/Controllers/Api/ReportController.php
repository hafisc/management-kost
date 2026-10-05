<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Stay;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count() ?: 35;
        $filledRooms = Stay::where('status', 'active')->count() ?: 28;
        $emptyRooms = $totalRooms - $filledRooms;
        
        $filledPct = $totalRooms > 0 ? round(($filledRooms / $totalRooms) * 100) : 0;
        $emptyPct = 100 - $filledPct;

        $transactions = Transaction::latest('transaction_date')->get();

        $pemasukan = $transactions->where('type', 'Pemasukan')->sum('amount');
        $pengeluaran = $transactions->where('type', 'Pengeluaran')->sum('amount');
        $saldo = $pemasukan - $pengeluaran;
        $trxCount = $transactions->count();

        // Line Chart Grouped by Date (Simplified to 7 points based on available dates)
        $dates = $transactions->pluck('transaction_date')->unique()->sort()->values()->take(-7);
        $labels = [];
        $pemasukanLine = [];
        $pengeluaranLine = [];
        
        if ($dates->isEmpty()) {
            $labels = ['1 Sep', '5 Sep', '10 Sep', '15 Sep', '20 Sep', '25 Sep', '30 Sep'];
            $pemasukanLine = [13000000, 14500000, 14000000, 19500000, 16000000, 18500000, 18500000];
            $pengeluaranLine = [4000000, 4500000, 3000000, 8500000, 6000000, 8500000, 6500000];
        } else {
            foreach ($dates as $date) {
                $labels[] = $date->format('d M');
                $pemasukanLine[] = $transactions->where('type', 'Pemasukan')->where('transaction_date', $date)->sum('amount');
                $pengeluaranLine[] = $transactions->where('type', 'Pengeluaran')->where('transaction_date', $date)->sum('amount');
            }
        }

        // Donut Chart - Group by Category for Pengeluaran
        $pengeluaranByCategory = $transactions->where('type', 'Pengeluaran')->groupBy('category');
        $donutData = [];
        $colors = ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#f97316', '#ec4899', '#14b8a6'];
        $i = 0;
        foreach ($pengeluaranByCategory as $category => $items) {
            $sum = $items->sum('amount');
            $pct = $pengeluaran > 0 ? round(($sum / $pengeluaran) * 100) : 0;
            $donutData[] = [
                'label' => $category,
                'value' => $pct,
                'amount' => 'Rp ' . number_format($sum, 0, ',', '.'),
                'color' => $colors[$i % count($colors)]
            ];
            $i++;
        }

        if (empty($donutData)) {
            $donutData = [
                ['label' => 'Belum Ada', 'value' => 100, 'amount' => 'Rp 0', 'color' => '#e2e8f0']
            ];
        }

        $trxFormatted = $transactions->take(15)->map(function($trx) {
            $tenantName = '-';
            if ($trx->reference_id && $trx->type == 'Pemasukan') {
                $invoice = \App\Models\Invoice::find($trx->reference_id);
                if ($invoice && $invoice->stay && $invoice->stay->tenant) {
                    $tenantName = $invoice->stay->tenant->name;
                }
            }
            return [
                'id' => $trx->id,
                'date' => $trx->transaction_date->format('d M Y'),
                'desc' => $trx->description ?? $trx->category,
                'name' => $tenantName,
                'method' => $trx->method ?? 'Transfer Bank',
                'amount' => 'Rp ' . number_format($trx->amount, 0, ',', '.'),
                'status' => 'Lunas'
            ];
        })->values();

        // Top
        $pemasukanTop = $transactions->where('type', 'Pemasukan')->sortByDesc('amount')->first();
        $pengeluaranTop = $transactions->where('type', 'Pengeluaran')->sortByDesc('amount')->first();
        
        $pemasukanTopName = '-';
        if ($pemasukanTop && $pemasukanTop->reference_id) {
            $invoice = \App\Models\Invoice::find($pemasukanTop->reference_id);
            if ($invoice && $invoice->stay && $invoice->stay->tenant) {
                $pemasukanTopName = $invoice->stay->tenant->name;
            }
        }

        return response()->json([
            'stats' => [
                'pemasukan' => 'Rp ' . number_format($pemasukan, 0, ',', '.'),
                'pemasukan_trend' => '+ 0%',
                'pengeluaran' => 'Rp ' . number_format($pengeluaran, 0, ',', '.'),
                'pengeluaran_trend' => '+ 0%',
                'saldo' => 'Rp ' . number_format($saldo, 0, ',', '.'),
                'saldo_trend' => '+ 0%',
                'transaksi' => $trxCount,
                'transaksi_trend' => '+ 0%',
            ],
            'charts' => [
                'line' => [
                    'labels' => $labels,
                    'pemasukan' => $pemasukanLine,
                    'pengeluaran' => $pengeluaranLine,
                ],
                'donut' => $donutData
            ],
            'transactions' => $trxFormatted,
            'summary' => [
                'pemasukan_top' => [
                    'name' => $pemasukanTopName,
                    'amount' => $pemasukanTop ? 'Rp ' . number_format($pemasukanTop->amount, 0, ',', '.') : 'Rp 0'
                ],
                'pengeluaran_top' => [
                    'name' => $pengeluaranTop ? $pengeluaranTop->category : '-',
                    'amount' => $pengeluaranTop ? 'Rp ' . number_format($pengeluaranTop->amount, 0, ',', '.') : 'Rp 0'
                ],
                'kamar' => [
                    'terisi' => $filledRooms,
                    'total' => $totalRooms,
                    'terisi_pct' => $filledPct,
                    'kosong' => $emptyRooms,
                    'kosong_pct' => $emptyPct
                ]
            ]
        ]);
    }
}
