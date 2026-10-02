<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default Super Admin
        User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@kostsumberrezeki.com',
            'password' => bcrypt('sumberrezeki1515'),
        ]);

        // Seed buildings, floors, and rooms
        $this->call([
            BuildingSeeder::class,
        ]);
    }
}
