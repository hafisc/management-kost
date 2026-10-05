<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Stay;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create Rooms & Tenants for Gedung Putri (35 kamar)
        for ($i = 1; $i <= 35; $i++) {
            $floor = 1;
            if ($i > 12 && $i <= 24) $floor = 2;
            if ($i > 24) $floor = 3;

            $room = Room::create([
                'building' => 'Gedung Putri',
                'floor'    => $floor,
                'number'   => str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => 2,
            ]);

            $this->createTenantForRoom($room, $faker);
        }

        // Create Rooms & Tenants for Gedung Putra (3 kamar)
        for ($i = 1; $i <= 3; $i++) {
            $room = Room::create([
                'building' => 'Gedung Putra',
                'floor'    => 1,
                'number'   => str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => 2,
            ]);

            $this->createTenantForRoom($room, $faker);
        }
    }

    private function createTenantForRoom(Room $room, $faker)
    {
        // 1. Create Tenant
        $tenant = Tenant::create([
            'name'     => $faker->name($room->building === 'Gedung Putri' ? 'female' : 'male'),
            'whatsapp' => $faker->unique()->numerify('08##########'),
            'status'   => 'Aktif',
            'notes'    => $faker->randomElement(['Membawa Rice Cooker', 'Pulang malam', 'Bawa Kipas Angin', null])
        ]);

        // 2. Create Stay
        Stay::create([
            'tenant_id'      => $tenant->id,
            'room_id'        => $room->id,
            'check_in'       => now()->subDays(rand(1, 60))->format('Y-m-d'),
            'check_out'      => now()->addDays(rand(10, 30))->format('Y-m-d'),
            'duration'       => '1 Bulan',
            'occupancy_type' => 1,
            'payment_status' => $faker->randomElement(['Lunas', 'Lunas', 'Lunas', 'Pending']),
            'status'         => 'Aktif',
            'notes'          => $tenant->notes,
        ]);
    }
}
