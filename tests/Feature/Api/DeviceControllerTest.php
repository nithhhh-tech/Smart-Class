<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Room;
use App\Models\Device;
use App\Models\DeviceCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_request_to_devices_is_blocked(): void
    {
        $response = $this->getJson('/api/devices');
        $response->assertStatus(401);
    }

    public function test_can_list_devices(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();
        Device::factory()->create(['room_id' => $room->id, 'name' => 'Light 1']);
        Device::factory()->create(['room_id' => $room->id, 'name' => 'Fan 1']);

        $response = $this->getJson('/api/devices');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonPath('data.0.name', 'Light 1')
                 ->assertJsonPath('data.1.name', 'Fan 1');
    }

    public function test_can_list_devices_filtered_by_room(): void
    {
        Sanctum::actingAs($this->user);

        $room1 = Room::factory()->create();
        $room2 = Room::factory()->create();

        Device::factory()->create(['room_id' => $room1->id, 'name' => 'Room 1 Light']);
        Device::factory()->create(['room_id' => $room2->id, 'name' => 'Room 2 Light']);

        $response = $this->getJson("/api/devices?room_id={$room1->id}");

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name', 'Room 1 Light');
    }

    public function test_can_create_device(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/devices', [
            'room_id' => $room->id,
            'name' => 'Ceiling Fan 1',
            'type' => 'fan',
            'status' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'data'])
                 ->assertJsonPath('data.name', 'Ceiling Fan 1');

        $this->assertDatabaseHas('devices', [
            'room_id' => $room->id,
            'name' => 'Ceiling Fan 1',
            'type' => 'fan',
            'status' => 1,
        ]);
    }

    public function test_cannot_create_device_with_invalid_type(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/devices', [
            'room_id' => $room->id,
            'name' => 'Air Conditioner',
            'type' => 'ac', // invalid type, should be light or fan
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['type']);
    }

    public function test_can_show_device_with_room(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create(['name' => 'Physics Lab']);
        $device = Device::factory()->create([
            'room_id' => $room->id,
            'name' => 'Smart Light 1',
        ]);

        $response = $this->getJson("/api/devices/{$device->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Smart Light 1')
                 ->assertJsonPath('data.room.name', 'Physics Lab');
    }

    public function test_can_update_device(): void
    {
        Sanctum::actingAs($this->user);

        $device = Device::factory()->create(['name' => 'Old Device Name']);

        $response = $this->putJson("/api/devices/{$device->id}", [
            'name' => 'New Device Name',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'New Device Name');

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'name' => 'New Device Name',
        ]);
    }

    public function test_can_delete_device(): void
    {
        Sanctum::actingAs($this->user);

        $device = Device::factory()->create();

        $response = $this->deleteJson("/api/devices/{$device->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Device deleted');

        $this->assertDatabaseMissing('devices', [
            'id' => $device->id,
        ]);
    }

    public function test_can_toggle_device_queues_command(): void
    {
        Sanctum::actingAs($this->user);

        $device = Device::factory()->create(['status' => true]); // device is currently on

        $response = $this->postJson("/api/devices/{$device->id}/toggle");

        $response->assertStatus(200)
                 ->assertJsonPath('message', "Toggle command 'off' queued")
                 ->assertJsonStructure(['data' => ['id', 'device_id', 'command', 'status']]);

        $this->assertDatabaseHas('device_commands', [
            'device_id' => $device->id,
            'command' => 'off',
            'status' => 'pending',
        ]);
    }

    public function test_toggle_considers_pending_command(): void
    {
        Sanctum::actingAs($this->user);

        $device = Device::factory()->create(['status' => false]); // device is off

        // but there is already a pending "on" command
        DeviceCommand::factory()->create([
            'device_id' => $device->id,
            'command' => 'on',
            'status' => 'pending',
        ]);

        // Toggling it should queue an "off" command since the pending one would turn it "on"
        $response = $this->postJson("/api/devices/{$device->id}/toggle");

        $response->assertStatus(200)
                 ->assertJsonPath('message', "Toggle command 'off' queued");

        $this->assertDatabaseHas('device_commands', [
            'device_id' => $device->id,
            'command' => 'off',
            'status' => 'pending',
        ]);
    }
}
