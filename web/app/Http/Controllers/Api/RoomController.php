<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\RoomService;

class RoomController extends Controller
{
    public function __construct(private RoomService $roomService) {}

    public function index()
    {
        return Room::with(['building', 'floor'])
            ->where('is_active', true)
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'building' => $room->building->name,
                    'floor' => $room->floor->number,
                    'is_available' => $this->roomService->isRoomAvailable($room->id),
                    'occupancy' => $this->roomService->calculateOccupancy($room->id),
                ];
            });
    }

    public function available()
    {
        $availableRooms = $this->roomService->getAvailableRooms();

        return response()->json([
            'available_rooms' => $availableRooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'building' => $room->building->name,
                    'floor' => $room->floor->number,
                ];
            }),
        ]);
    }

    public function show($id)
    {
        $room = Room::with(['building', 'floor', 'stays.tenant'])->findOrFail($id);

        return response()->json([
            'id' => $room->id,
            'name' => $room->name,
            'building' => $room->building->name,
            'floor' => $room->floor->number,
            'is_available' => $this->roomService->isRoomAvailable($room->id),
            'occupancy' => $this->roomService->calculateOccupancy($room->id),
            'current_tenants' => $room->stays()->active()->with('tenant')->get(),
        ]);
    }
}
