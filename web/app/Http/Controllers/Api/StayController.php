<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stay;
use Illuminate\Http\Request;

class StayController extends Controller
{
    public function index(Request $request)
    {
        $query = Stay::with(['tenant', 'room.building']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        return $query->orderBy('start_at', 'desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $stay = Stay::create($validated);

        return response()->json($stay->load(['tenant', 'room']), 201);
    }

    public function show($id)
    {
        $stay = Stay::with(['tenant', 'room.building.floor'])->findOrFail($id);

        return response()->json($stay);
    }

    public function update(Request $request, $id)
    {
        $stay = Stay::findOrFail($id);

        $validated = $request->validate([
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $stay->update($validated);

        return response()->json($stay->load(['tenant', 'room']));
    }

    public function active()
    {
        $activeStays = Stay::active()->with(['tenant', 'room.building'])->get();

        return response()->json($activeStays);
    }
}
