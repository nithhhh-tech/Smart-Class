<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Room;
use App\Models\Device;
use App\Models\Schedule;
use App\Models\DeviceCommand;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_request_to_schedules_is_blocked(): void
    {
        $response = $this->getJson('/api/schedules');
        $response->assertStatus(401);
    }

    public function test_can_list_schedules(): void
    {
        Sanctum::actingAs($this->user);

        $schedule = Schedule::factory()->create();

        $response = $this->getJson('/api/schedules');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.id', $schedule->id);
    }

    public function test_can_list_schedules_filtered_by_room(): void
    {
        Sanctum::actingAs($this->user);

        $room1 = Room::factory()->create();
        $room2 = Room::factory()->create();

        $schedule1 = Schedule::factory()->create(['room_id' => $room1->id]);
        $schedule2 = Schedule::factory()->create(['room_id' => $room2->id]);

        $response = $this->getJson("/api/schedules?room_id={$room1->id}");

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.id', $schedule1->id);
    }

    public function test_can_create_schedule(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();
        $device = Device::factory()->create(['room_id' => $room->id]);

        $response = $this->postJson('/api/schedules', [
            'room_id' => $room->id,
            'device_id' => $device->id,
            'action' => 'off',
            'run_at' => '18:30',
            'days' => 'mon,tue,wed',
            'is_active' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.action', 'off')
                 ->assertJsonPath('data.run_at', '18:30');

        $this->assertDatabaseHas('schedules', [
            'room_id' => $room->id,
            'device_id' => $device->id,
            'action' => 'off',
            'run_at' => '18:30',
            'days' => 'mon,tue,wed',
            'is_active' => 1,
        ]);
    }

    public function test_can_update_schedule(): void
    {
        Sanctum::actingAs($this->user);

        $schedule = Schedule::factory()->create(['is_active' => true]);

        $response = $this->putJson("/api/schedules/{$schedule->id}", [
            'is_active' => false,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'is_active' => 0,
        ]);
    }

    public function test_can_delete_schedule(): void
    {
        Sanctum::actingAs($this->user);

        $schedule = Schedule::factory()->create();

        $response = $this->deleteJson("/api/schedules/{$schedule->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('schedules', [
            'id' => $schedule->id,
        ]);
    }

    public function test_process_schedules_artisan_command(): void
    {
        // Fix time to a specific Monday at 14:30
        Carbon::setTestNow(Carbon::parse('2026-07-06 14:30:00')); // July 6, 2026 is a Monday

        $room = Room::factory()->create();
        $device = Device::factory()->create(['room_id' => $room->id]);

        // 1. Matching Active Schedule
        $matchingSchedule = Schedule::factory()->create([
            'room_id' => $room->id,
            'device_id' => $device->id,
            'action' => 'on',
            'run_at' => '14:30',
            'days' => 'mon,wed,fri',
            'is_active' => true,
        ]);

        // 2. Inactive Matching Schedule (should not trigger)
        $inactiveSchedule = Schedule::factory()->create([
            'room_id' => $room->id,
            'device_id' => $device->id,
            'action' => 'off',
            'run_at' => '14:30',
            'days' => 'mon,wed,fri',
            'is_active' => false,
        ]);

        // 3. Different Time Schedule (should not trigger)
        $diffTimeSchedule = Schedule::factory()->create([
            'room_id' => $room->id,
            'device_id' => $device->id,
            'action' => 'off',
            'run_at' => '14:45',
            'days' => 'mon,wed,fri',
            'is_active' => true,
        ]);

        // 4. Different Day Schedule (should not trigger on Mon)
        $diffDaySchedule = Schedule::factory()->create([
            'room_id' => $room->id,
            'device_id' => $device->id,
            'action' => 'off',
            'run_at' => '14:30',
            'days' => 'tue,thu',
            'is_active' => true,
        ]);

        // Run the artisan command
        $this->artisan('app:process-schedules')
             ->expectsOutput('Scanning schedules matching Day: mon | Time: 14:30')
             ->expectsOutput("Queued scheduled command 'on' for Device ID {$device->id} (Schedule #{$matchingSchedule->id})")
             ->expectsOutput('Scan completed. Queued 1 command(s).')
             ->assertExitCode(0);

        // Verify only 1 pending device command was created (for the matching schedule)
        $this->assertDatabaseHas('device_commands', [
            'device_id' => $device->id,
            'command' => 'on',
            'status' => 'pending',
        ]);

        // Verify no other command was created
        $this->assertEquals(1, DeviceCommand::count());

        // Clear test now helper
        Carbon::setTestNow();
    }
}
