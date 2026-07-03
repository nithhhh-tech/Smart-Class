<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Room;
use App\Models\Alert;
use App\Models\SensorLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AlertControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_request_to_alerts_is_blocked(): void
    {
        $response = $this->getJson('/api/alerts');
        $response->assertStatus(401);
    }

    public function test_can_list_alerts(): void
    {
        Sanctum::actingAs($this->user);

        $alert = Alert::factory()->create();

        $response = $this->getJson('/api/alerts');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.id', $alert->id);
    }

    public function test_can_list_alerts_filtered_by_room(): void
    {
        Sanctum::actingAs($this->user);

        $room1 = Room::factory()->create();
        $room2 = Room::factory()->create();

        $alert1 = Alert::factory()->create(['room_id' => $room1->id]);
        $alert2 = Alert::factory()->create(['room_id' => $room2->id]);

        $response = $this->getJson("/api/alerts?room_id={$room1->id}");

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.id', $alert1->id);
    }

    public function test_can_dismiss_alert(): void
    {
        Sanctum::actingAs($this->user);

        $alert = Alert::factory()->create();

        $response = $this->deleteJson("/api/alerts/{$alert->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Alert dismissed successfully');

        $this->assertDatabaseMissing('alerts', [
            'id' => $alert->id,
        ]);
    }

    public function test_high_temperature_triggers_alert(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 36.5, // > 35.0
            'humidity' => 50.0,
            'motion' => false,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('alerts', [
            'room_id' => $room->id,
            'type' => 'temperature',
            'message' => 'Critical Temperature Warning: High temperature (36.5°C) recorded in the classroom.',
        ]);
    }

    public function test_normal_temperature_does_not_trigger_alert(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 24.5, // safe temp
            'humidity' => 50.0,
            'motion' => false,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseMissing('alerts', [
            'room_id' => $room->id,
            'type' => 'temperature',
        ]);
    }

    public function test_high_humidity_triggers_alert(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 25.0,
            'humidity' => 88.5, // > 85.0
            'motion' => false,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('alerts', [
            'room_id' => $room->id,
            'type' => 'humidity',
            'message' => 'High Humidity Alert: Excessive moisture levels (88.5%) detected.',
        ]);
    }

    public function test_low_humidity_triggers_alert(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 25.0,
            'humidity' => 15.2, // < 20.0
            'motion' => false,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('alerts', [
            'room_id' => $room->id,
            'type' => 'humidity',
            'message' => 'Low Humidity Alert: Dry air levels (15.2%) detected.',
        ]);
    }

    public function test_off_hours_motion_triggers_alert(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        // Lock time to off-hours (10:30 PM = 22:30)
        Carbon::setTestNow(Carbon::parse('2026-07-03 22:30:00'));

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 25.0,
            'humidity' => 50.0,
            'motion' => true,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('alerts', [
            'room_id' => $room->id,
            'type' => 'motion',
            'message' => 'Intrusion Warning: Off-hours motion detected at 22:30.',
        ]);

        Carbon::setTestNow();
    }

    public function test_business_hours_motion_does_not_trigger_alert(): void
    {
        Sanctum::actingAs($this->user);

        $room = Room::factory()->create();

        // Lock time to business-hours (11:30 AM = 11:30)
        Carbon::setTestNow(Carbon::parse('2026-07-03 11:30:00'));

        $response = $this->postJson('/api/sensor-logs', [
            'room_id' => $room->id,
            'temperature' => 25.0,
            'humidity' => 50.0,
            'motion' => true,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseMissing('alerts', [
            'room_id' => $room->id,
            'type' => 'motion',
        ]);

        Carbon::setTestNow();
    }
}
