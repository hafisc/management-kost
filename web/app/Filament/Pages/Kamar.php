<?php

namespace App\Filament\Pages;

use App\Models\Building;
use App\Models\Room;
use App\Models\Stay;
use Filament\Pages\Page;

class Kamar extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Kamar';

    protected static ?string $title = 'Manajemen Kamar';

    protected string $view = 'filament.pages.kamar';

    public $selectedBuilding = 'all';

    public $selectedStatus = 'all';

    public $selectedRoom = null;

    public function mount(): void
    {
        //
    }

    public function getStats(): array
    {
        $total = Room::where('is_active', true)->count();
        $occupied = Stay::where('status', 'ACTIVE')
            ->distinct('room_id')
            ->count('room_id');
        $expiring = Stay::where('status', 'EXPIRING')
            ->distinct('room_id')
            ->count('room_id');
        $available = $total - $occupied;

        return [
            'total' => $total,
            'occupied' => $occupied,
            'available' => $available,
            'expiring' => $expiring,
        ];
    }

    public function getRooms()
    {
        $query = Room::with(['building', 'floor'])
            ->where('is_active', true);

        if ($this->selectedBuilding !== 'all') {
            $query->where('building_id', $this->selectedBuilding);
        }

        $rooms = $query->orderBy('building_id')
            ->orderBy('floor_id')
            ->orderBy('room_number')
            ->get();

        // Attach occupancy status
        return $rooms->map(function ($room) {
            $activeStay = Stay::where('room_id', $room->id)
                ->whereIn('status', ['ACTIVE', 'EXPIRING'])
                ->with('tenant')
                ->first();

            $room->occupancy_status = $activeStay ? $activeStay->status : 'AVAILABLE';
            $room->current_tenant = $activeStay?->tenant;
            $room->current_stay = $activeStay;

            return $room;
        })->filter(function ($room) {
            if ($this->selectedStatus === 'all') {
                return true;
            }

            return $room->occupancy_status === strtoupper($this->selectedStatus);
        });
    }

    public function getRoomsByBuilding()
    {
        $buildings = Building::with(['floors' => function ($query) {
            $query->orderBy('floor_number');
        }])->get();

        $grouped = [];

        foreach ($buildings as $building) {
            if ($this->selectedBuilding !== 'all' && $building->id != $this->selectedBuilding) {
                continue;
            }

            foreach ($building->floors as $floor) {
                $rooms = $this->getRooms()->filter(function ($room) use ($building, $floor) {
                    return $room->building_id == $building->id && $room->floor_id == $floor->id;
                });

                if ($rooms->count() > 0) {
                    $grouped[$building->name][$floor->floor_number] = $rooms;
                }
            }
        }

        return $grouped;
    }

    public function selectRoom($roomId): void
    {
        $this->selectedRoom = Room::with(['building', 'floor'])
            ->find($roomId);

        if ($this->selectedRoom) {
            $activeStay = Stay::where('room_id', $this->selectedRoom->id)
                ->whereIn('status', ['ACTIVE', 'EXPIRING'])
                ->with(['tenant', 'payments'])
                ->first();

            $this->selectedRoom->current_stay = $activeStay;
            $this->selectedRoom->current_tenant = $activeStay?->tenant;
            $this->selectedRoom->occupancy_status = $activeStay ? $activeStay->status : 'AVAILABLE';
        }

        $this->dispatch('open-modal', id: 'room-detail');
    }

    public function closeModal(): void
    {
        $this->selectedRoom = null;
        $this->dispatch('close-modal', id: 'room-detail');
    }
}
