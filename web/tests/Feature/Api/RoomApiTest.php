<?php

namespace Tests\Feature\Api;

use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoomApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Authenticate with Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_list_rooms(): void
    {
        $building = Building::factory()->create();
        $floor = Floor::factory()->create(['building_id' => $building->id]);
        Room::factory()->count(3)->create([
            'building_id' => $building->id,
            'floor_id' => $floor->id,
        ]);

        $response = $this->getJson('/api/v1/rooms');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_get_room_details(): void
    {
        $building = Building::factory()->create();
        $floor = Floor::factory()->create(['building_id' => $building->id]);
        $room = Room::factory()->create([
            'building_id' => $building->id,
            'floor_id' => $floor->id,
            'name' => 'P-101',
        ]);

        $response = $this->getJson("/api/v1/rooms/{$room->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'P-101']);
    }
}
