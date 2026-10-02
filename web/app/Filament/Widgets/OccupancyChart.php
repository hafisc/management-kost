<?php

namespace App\Filament\Widgets;

use App\Models\Building;
use App\Models\Stay;
use Filament\Widgets\ChartWidget;

class OccupancyChart extends ChartWidget
{
    protected ?string $heading = 'Tingkat Okupansi per Gedung';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $buildings = Building::with(['rooms' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        $labels = [];
        $totalRooms = [];
        $occupiedRooms = [];

        foreach ($buildings as $building) {
            $labels[] = $building->name;
            $total = $building->rooms->count();
            $totalRooms[] = $total;

            $occupied = Stay::whereHas('room', function ($query) use ($building) {
                $query->where('building_id', $building->id);
            })->where('status', 'ACTIVE')->distinct('room_id')->count('room_id');

            $occupiedRooms[] = $occupied;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Kamar',
                    'data' => $totalRooms,
                    'backgroundColor' => 'rgba(4, 43, 96, 0.5)',
                ],
                [
                    'label' => 'Kamar Terisi',
                    'data' => $occupiedRooms,
                    'backgroundColor' => '#FCB502',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
