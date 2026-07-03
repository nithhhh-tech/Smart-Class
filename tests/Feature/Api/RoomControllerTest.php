<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Room;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_request_is_blocked(): void
    {
        $response = $this->getJson('/api/rooms');
        $response->assertStatus(401);
    }

    public function test_can_list_rooms_with_device_counts(): void
    {
        Sanctum::actingAs($this->user);

        $room1 = Room::factory()->create(['name' => 'Room A']);
        $room2 = Room::factory()->create(['name' => 'Room B']);

        Device::factory()->create(['room_id' => $room1->id]);
        Device::factory()->create(['room_id' => $room1->id]);

        $response = $this->getJson('/api/rooms');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonPath('data.0.name', 'Room A')
                 ->assertJsonPath('data.0.devices_count', 2)
                 ->assertJsonPath('data.1.name', 'Room B')
                 ->assertJsonPath('data.1.devices_count', 0);
    }

    public function test_can_create_room(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/rooms', [
            'name' => 'Classroom 101',
            'location' => 'Building A, First Floor',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'data'])
                 ->assertJsonPath('data.name', 'Classroom 101');

        $this->assertDatabaseHas('rooms', [
            'name' => 'Classroom 101',
            'location' => 'Building A, First Floor',
        ]);
    }

    public function test_cannot_create_room_with_missing_name(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/rooms', [
            'location' => 'Somewhere',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_room_with_devices(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create(['name' => 'Lab 1']);
        $device = Device::factory()->create(['room_id' => $room->id, 'name' => 'Smart Light']);

        $response = $this->getJson("/api/rooms/{$room->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Lab 1')
                 ->assertJsonCount(1, 'data.devices')
                 ->assertJsonPath('data.devices.0.name', 'Smart Light');
    }

    public function test_can_update_room(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create([
            'name' => 'Old Name',
            'location' => 'Old Location',
        ]);

        $response = $this->putJson("/api/rooms/{$room->id}", [
            'name' => 'New Name',
            'location' => 'New Location',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'New Name')
                 ->assertJsonPath('data.location', 'New Location');

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'name' => 'New Name',
            'location' => 'New Location',
        ]);
    }

    public function test_can_delete_room(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->deleteJson("/api/rooms/{$room->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Room deleted');

        $this->assertDatabaseMissing('rooms', [
            'id' => $room->id,
        ]);
    }
}
