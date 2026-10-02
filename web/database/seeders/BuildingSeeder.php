<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        // Building Putri (Gedung Putri - 3 lantai, ~35 kamar)
        $putri = Building::create([
            'name' => 'Gedung Putri',
            'code' => 'P',
            'description' => 'Gedung kost khusus putri dengan 3 lantai',
            'is_active' => true,
        ]);

        // Building Putra (Gedung Putra - 1 lantai, 3 kamar)
        $putra = Building::create([
            'name' => 'Gedung Putra',
            'code' => 'PA',
            'description' => 'Gedung kost khusus putra terpisah dari gedung putri',
            'is_active' => true,
        ]);

        // Create floors and rooms for Putri (3 lantai)
        for ($floorNumber = 1; $floorNumber <= 3; $floorNumber++) {
            $floor = Floor::create([
                'building_id' => $putri->id,
                'floor_number' => $floorNumber,
                'name' => "Lantai {$floorNumber}",
            ]);

            // Create ~12 rooms per floor (total ~36 rooms, close to 35)
            $roomsPerFloor = $floorNumber === 3 ? 11 : 12; // Floor 3 has 11 rooms = total 35

            for ($roomNum = 1; $roomNum <= $roomsPerFloor; $roomNum++) {
                $roomNumber = str_pad($roomNum, 2, '0', STR_PAD_LEFT);
                $capacity = ($roomNum % 3 === 0) ? 2 : 1; // Every 3rd room is sharing (capacity 2)

                Room::create([
                    'building_id' => $putri->id,
                    'floor_id' => $floor->id,
                    'room_number' => "P-{$floorNumber}{$roomNumber}",
                    'capacity' => $capacity,
                    'is_active' => true,
                ]);
            }
        }

        // Create floor and rooms for Putra (1 lantai, 3 kamar)
        $putraFloor = Floor::create([
            'building_id' => $putra->id,
            'floor_number' => 1,
            'name' => 'Lantai 1',
        ]);

        for ($roomNum = 1; $roomNum <= 3; $roomNum++) {
            Room::create([
                'building_id' => $putra->id,
                'floor_id' => $putraFloor->id,
                'room_number' => "PA-{$roomNum}",
                'capacity' => 1,
                'is_active' => true,
            ]);
        }
    }
}
