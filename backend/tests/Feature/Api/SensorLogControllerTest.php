<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Room;
use App\Models\SensorLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SensorLogControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_request_to_sensor_logs_is_blocked(): void
    {
        $response = $this->postJson('/api/sensor-logs', []);
        $response->assertStatus(401);
    }

    public function test_can_store_sensor_log(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 24.5,
            'humidity' => 60.2,
            'motion' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Logged')
                 ->assertJsonPath('data.room_id', $room->id)
                 ->assertJsonPath('data.temperature', 24.5)
                 ->assertJsonPath('data.humidity', 60.2)
                 ->assertJsonPath('data.motion', true);

        $this->assertDatabaseHas('sensor_logs', [
            'room_id' => $room->id,
            'temperature' => 24.5,
            'humidity' => 60.2,
            'motion' => 1,
        ]);
    }

    public function test_cannot_store_sensor_log_with_missing_room(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/sensor-logs', [
            'temperature' => 24.5,
            'humidity' => 60.2,
            'motion' => true,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['room_id']);
    }

    public function test_can_get_latest_sensor_log(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        // Create an older log
        SensorLog::factory()->create([
            'room_id' => $room->id,
            'temperature' => 20.0,
            'recorded_at' => now()->subMinutes(10),
        ]);

        // Create a newer log
        $latestLog = SensorLog::factory()->create([
            'room_id' => $room->id,
            'temperature' => 25.5,
            'recorded_at' => now(),
        ]);

        $response = $this->getJson("/api/sensor-logs/latest?room_id={$room->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.temperature', 25.5)
                 ->assertJsonPath('data.id', $latestLog->id);
    }

    public function test_can_get_sensor_logs_history(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        // Log within the 24 hour window
        $log1 = SensorLog::factory()->create([
            'room_id' => $room->id,
            'recorded_at' => now()->subHours(5),
        ]);
        $log2 = SensorLog::factory()->create([
            'room_id' => $room->id,
            'recorded_at' => now()->subHours(2),
        ]);

        // Log outside the 24 hour window
        SensorLog::factory()->create([
            'room_id' => $room->id,
            'recorded_at' => now()->subHours(25),
        ]);

        $response = $this->getJson("/api/sensor-logs/history?room_id={$room->id}&hours=24");

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonPath('data.0.id', $log1->id)
                 ->assertJsonPath('data.1.id', $log2->id);
    }

    public function test_can_get_dashboard_summary(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $latestLog = SensorLog::factory()->create([
            'room_id' => $room->id,
            'temperature' => 22.8,
            'humidity' => 55.4,
            'motion' => false,
            'recorded_at' => now(),
        ]);

        $response = $this->getJson("/api/dashboard/summary?room_id={$room->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.temperature', 22.8)
                 ->assertJsonPath('data.humidity', 55.4)
                 ->assertJsonPath('data.motion', false);
    }
}
