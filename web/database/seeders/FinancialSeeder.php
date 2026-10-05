<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stay;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use Carbon\Carbon;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stays = Stay::with(['tenant', 'room'])->get();
        $invoiceCount = 1;

        foreach ($stays as $stay) {
            $isPaid = rand(0, 100) > 20; // 80% chance paid
            $status = $isPaid ? 'Paid' : (rand(0,10) > 5 ? 'Pending' : 'Failed');
            $method = ['QRIS', 'Transfer Bank', 'Cash'][rand(0, 2)];
            $amount = $stay->room->price ?? 600000;
            $start = Carbon::parse($stay->check_in);
            $end = $start->copy()->addMonth();

            $invoice = Invoice::create([
                'stay_id' => $stay->id,
                'invoice_number' => 'INV-2025090' . str_pad($invoiceCount++, 2, '0', STR_PAD_LEFT),
                'total_amount' => $amount,
                'status' => $status,
                'payment_method' => $isPaid ? $method : null,
                'paid_at' => $isPaid ? now()->subDays(rand(1, 15)) : null,
                'due_date' => $start->copy()->addDays(3),
                'period_start' => $start,
                'period_end' => $end,
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => 'Sewa Kost (1 bulan)',
                'amount' => $amount,
            ]);

            // Add additional items sometimes
            if (rand(0, 10) > 7) {
                $extra = 25000;
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'name' => 'Biaya Tambahan (Listrik)',
                    'amount' => $extra,
                ]);
                $invoice->update(['total_amount' => $amount + $extra]);
            }

            // Create Transaction if paid
            if ($isPaid) {
                Transaction::create([
                    'type' => 'Pemasukan',
                    'category' => 'Sewa Kost',
                    'amount' => $invoice->total_amount,
                    'description' => 'Pembayaran Kost (' . ($stay->room->building ?? 'K') . '-' . $stay->room->number . ')',
                    'reference_id' => $invoice->id,
                    'transaction_date' => $invoice->paid_at,
                    'method' => $method,
                ]);
            }
        }

        // Add some expenses
        $expenses = [
            ['Listrik', 1184000],
            ['WiFi', 512000],
            ['Air', 384000],
            ['Perawatan', 288000],
            ['Lainnya', 832000]
        ];

        foreach ($expenses as $expense) {
            Transaction::create([
                'type' => 'Pengeluaran',
                'category' => $expense[0],
                'amount' => $expense[1],
                'description' => 'Pembayaran ' . $expense[0] . ' Bulan Ini',
                'transaction_date' => now()->subDays(rand(1, 20)),
                'method' => 'Transfer Bank'
            ]);
        }
    }
}
