<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class PaymentController extends Controller
{
    public function index()
    {
        $invoicesData = Invoice::with(['stay.tenant', 'stay.room', 'items'])->latest()->get();
        
        $totalTagihan = $invoicesData->sum('total_amount');
        $totalInvoices = $invoicesData->count();
        
        $paidInvoices = $invoicesData->where('status', 'Paid');
        $sudahDibayar = $paidInvoices->sum('total_amount');
        $sudahDibayarPct = $totalTagihan > 0 ? round(($sudahDibayar / $totalTagihan) * 100) : 0;
        
        $pendingInvoices = $invoicesData->where('status', 'Pending');
        $pending = $pendingInvoices->sum('total_amount');
        $pendingPct = $totalTagihan > 0 ? round(($pending / $totalTagihan) * 100) : 0;
        
        $failedInvoices = $invoicesData->whereIn('status', ['Failed', 'Expired']);
        $gagal = $failedInvoices->sum('total_amount');
        $gagalPct = $totalTagihan > 0 ? round(($gagal / $totalTagihan) * 100) : 0;

        $invoices = $invoicesData->map(function($invoice) {
            $stay = $invoice->stay;
            $room = $stay->room;
            $tenant = $stay->tenant;

            $roomPrefix = $room ? (str_contains(strtolower($room->building), 'putri') ? 'P-' : 'L-') : '';
            $lantai = $room ? (str_contains(strtolower($room->building), 'putri') ? 'Lantai 2' : 'Lantai 1') : '';
            $gender = $room ? (str_contains(strtolower($room->building), 'putri') ? 'Putri' : 'Putra') : '';

            $items = $invoice->items->map(function($item) {
                return [
                    'name' => $item->name,
                    'amount' => 'Rp ' . number_format($item->amount, 0, ',', '.')
                ];
            });

            return [
                'id' => $invoice->id,
                'invoice' => $invoice->invoice_number,
                'tenant' => [
                    'name' => $tenant->name ?? '-',
                    'phone' => $tenant->phone ?? '-',
                ],
                'room' => [
                    'number' => $room ? $roomPrefix . str_pad($room->number, 2, '0', STR_PAD_LEFT) : '-',
                    'desc' => $gender . ' - ' . $lantai,
                ],
                'period' => [
                    'date' => $invoice->period_start->format('d M') . ' - ' . $invoice->period_end->format('d M Y'),
                    'duration' => '(1 bulan)'
                ],
                'total' => 'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
                'total_num' => $invoice->total_amount,
                'method' => $invoice->payment_method ?? '-',
                'status' => $invoice->status,
                'date' => $invoice->paid_at ? $invoice->paid_at->format('d M Y') : '-',
                'time' => $invoice->paid_at ? $invoice->paid_at->format('H:i') : '-',
                'items' => $items
            ];
        });

        return response()->json([
            'stats' => [
                'total_tagihan' => 'Rp ' . number_format($totalTagihan, 0, ',', '.'),
                'total_invoices' => $totalInvoices,
                'sudah_dibayar' => 'Rp ' . number_format($sudahDibayar, 0, ',', '.'),
                'sudah_dibayar_pct' => $sudahDibayarPct,
                'pending' => 'Rp ' . number_format($pending, 0, ',', '.'),
                'pending_pct' => $pendingPct,
                'gagal' => 'Rp ' . number_format($gagal, 0, ',', '.'),
                'gagal_pct' => $gagalPct,
            ],
            'invoices' => $invoices
        ]);
    }
}
