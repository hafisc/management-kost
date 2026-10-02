<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('tenant');

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('payment_date', $request->month)
                ->whereYear('payment_date', $request->year);
        }

        return $query->orderBy('payment_date', 'desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,e_wallet,other',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.description' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $payment = Payment::create($validated);

        if (! empty($validated['items'])) {
            $payment->items()->createMany($validated['items']);
        }

        return response()->json($payment->load('items'), 201);
    }

    public function show($id)
    {
        $payment = Payment::with(['tenant', 'items'])->findOrFail($id);

        return response()->json($payment);
    }
}
