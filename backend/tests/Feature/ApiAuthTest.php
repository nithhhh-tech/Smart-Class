<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_authenticated_user_can_access_dashboard_api_routes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/rooms');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
        ]);
    }
}
