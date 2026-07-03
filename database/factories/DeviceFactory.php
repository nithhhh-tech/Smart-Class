<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
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
            'name' => fake()->word() . ' ' . fake()->randomElement(['Bulb', 'Ceiling Fan', 'Wall Fan', 'Main Light']),
            'type' => fake()->randomElement(['light', 'fan']),
            'status' => fake()->boolean(),
        ];
    }
}
