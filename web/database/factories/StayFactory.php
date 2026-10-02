<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class StayFactory extends Factory
{
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('-2 months', 'now');
        $endAt = fake()->dateTimeBetween($startAt, '+2 months');

        return [
            'tenant_id' => Tenant::factory(),
            'room_id' => Room::inRandomOrder()->first()?->id ?? Room::factory(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'duration_type' => fake()->randomElement(['daily', 'weekly', 'monthly', 'custom']),
            'duration_value' => fake()->numberBetween(1, 30),
            'occupancy_type' => fake()->randomElement(['single', 'sharing']),
            'status' => 'ACTIVE',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
