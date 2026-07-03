<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_device_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'esp32@example.com',
            'password' => bcrypt('secret-password'),
        ]);

        $response = $this->postJson('/api/device/login', [
            'email' => 'esp32@example.com',
            'password' => 'secret-password',
            'device_name' => 'ESP32-Classroom-1',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'message'])
                 ->assertJsonPath('message', 'Login successful');

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_device_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'esp32@example.com',
            'password' => bcrypt('secret-password'),
        ]);

        $response = $this->postJson('/api/device/login', [
            'email' => 'esp32@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_device_login_validation_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/device/login', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }
}
