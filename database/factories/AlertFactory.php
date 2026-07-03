<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'type' => fake()->randomElement(['temperature', 'humidity', 'motion']),
            'message' => fake()->sentence(),
            'triggered_at' => now(),
        ];
    }
}
