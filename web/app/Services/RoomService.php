<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RoomService
{
    /**
     * Calculate room occupancy (active stays vs capacity)
     */
    public function calculateOccupancy(Room $room): array
    {
        $activeStays = $room->stays()
            ->where('status', 'ACTIVE')
            ->count();

        $capacity = $room->capacity;
        $percentage = $capacity > 0 ? round(($activeStays / $capacity) * 100) : 0;

        return [
            'active_stays' => $activeStays,
            'capacity' => $capacity,
            'available' => $capacity - $activeStays,
            'percentage' => $percentage,
            'status' => match (true) {
                $activeStays === 0 => 'available',
                $activeStays < $capacity => 'partial',
                default => 'full',
            },
        ];
    }

    /**
     * Get available rooms for date range
     */
    public function getAvailableRooms(Carbon $startDate, Carbon $endDate): Collection
    {
        return Room::where('is_active', true)
            ->whereDoesntHave('stays', function ($query) use ($startDate, $endDate) {
                $query->where('status', 'ACTIVE')
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_at', [$startDate, $endDate])
                            ->orWhereBetween('end_at', [$startDate, $endDate])
                            ->orWhere(function ($q2) use ($startDate, $endDate) {
                                $q2->where('start_at', '<=', $startDate)
                                    ->where('end_at', '>=', $endDate);
                            });
                    });
            })
            ->get();
    }

    /**
     * Check if room is available for date range
     */
    public function isRoomAvailable(
        Room $room,
        Carbon $startDate,
        Carbon $endDate,
        ?Stay $excludeStay = null
    ): bool {
        $query = $room->stays()
            ->where('status', 'ACTIVE')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_at', [$startDate, $endDate])
                    ->orWhereBetween('end_at', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_at', '<=', $startDate)
                            ->where('end_at', '>=', $endDate);
                    });
            });

        if ($excludeStay) {
            $query->where('id', '!=', $excludeStay->id);
        }

        return ! $query->exists();
    }
}
