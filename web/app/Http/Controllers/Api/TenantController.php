<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->with('stays')->paginate(20);
    }

    public function show($id)
    {
        $tenant = Tenant::with(['stays.room.building'])->findOrFail($id);

        return response()->json($tenant);
    }

    public function findByPhone($phone)
    {
        $tenant = Tenant::where('phone', $phone)->with('stays')->first();

        if (! $tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        return response()->json($tenant);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'id_card_number' => 'nullable|string|max:20',
        ]);

        $tenant = Tenant::create($validated);

        return response()->json($tenant, 201);
    }
}
