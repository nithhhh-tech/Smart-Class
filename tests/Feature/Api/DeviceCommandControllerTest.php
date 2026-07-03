<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Room;
use App\Models\Device;
use App\Models\DeviceCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeviceCommandControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_request_to_commands_is_blocked(): void
    {
        $response = $this->getJson('/api/commands/pending?room_id=1');
        $response->assertStatus(401);
    }

    public function test_can_list_pending_commands_for_room(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();
        $otherRoom = Room::factory()->create();

        $device1 = Device::factory()->create(['room_id' => $room->id, 'type' => 'light']);
        $device2 = Device::factory()->create(['room_id' => $room->id, 'type' => 'fan']);
        $otherDevice = Device::factory()->create(['room_id' => $otherRoom->id]);

        // Pending commands in our room
        $cmd1 = DeviceCommand::factory()->create([
            'device_id' => $device1->id,
            'command' => 'on',
            'status' => 'pending',
        ]);
        $cmd2 = DeviceCommand::factory()->create([
            'device_id' => $device2->id,
            'command' => 'off',
            'status' => 'pending',
        ]);

        // Executed command in our room (should not show up)
        DeviceCommand::factory()->create([
            'device_id' => $device1->id,
            'command' => 'off',
            'status' => 'executed',
        ]);

        // Pending command in another room (should not show up)
        DeviceCommand::factory()->create([
            'device_id' => $otherDevice->id,
            'command' => 'on',
            'status' => 'pending',
        ]);

        $response = $this->getJson("/api/commands/pending?room_id={$room->id}");

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonPath('data.0.id', $cmd1->id)
                 ->assertJsonPath('data.0.device_type', 'light')
                 ->assertJsonPath('data.0.command', 'on')
                 ->assertJsonPath('data.1.id', $cmd2->id)
                 ->assertJsonPath('data.1.device_type', 'fan')
                 ->assertJsonPath('data.1.command', 'off');
    }

    public function test_can_mark_command_as_executed_and_update_device_status(): void
    {
        Sanctum::actingAs($this->user);

        $device = Device::factory()->create(['status' => false]);
        $cmd = DeviceCommand::factory()->create([
            'device_id' => $device->id,
            'command' => 'on',
            'status' => 'pending',
        ]);

        $response = $this->putJson("/api/commands/{$cmd->id}/executed");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Command marked as executed');

        // Assert command updated to executed
        $this->assertDatabaseHas('device_commands', [
            'id' => $cmd->id,
            'status' => 'executed',
        ]);

        // Assert device updated to status = 1 (on)
        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'status' => 1,
        ]);
    }

    public function test_marking_off_command_updates_device_status_to_zero(): void
    {
        Sanctum::actingAs($this->user);

        $device = Device::factory()->create(['status' => true]);
        $cmd = DeviceCommand::factory()->create([
            'device_id' => $device->id,
            'command' => 'off',
            'status' => 'pending',
        ]);

        $response = $this->putJson("/api/commands/{$cmd->id}/executed");

        $response->assertStatus(200);

        // Assert device status updated to 0 (off)
        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'status' => 0,
        ]);
    }
}
