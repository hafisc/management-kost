<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'whatsapp_number' => '628'.fake()->numerify('##########'),
            'notes' => fake()->optional()->sentence(),
            'status' => 'ACTIVE',
        ];
    }
}
