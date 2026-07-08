<?php

namespace Database\Factories;

use App\Models\SensorLog;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SensorLog>
 */
class SensorLogFactory extends Factory
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
            'temperature' => fake()->randomFloat(2, 18, 35),
            'humidity' => fake()->randomFloat(2, 30, 90),
            'motion' => fake()->boolean(),
            'recorded_at' => now(),
        ];
    }
}
