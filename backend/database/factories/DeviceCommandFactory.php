<?php

namespace Database\Factories;

use App\Models\DeviceCommand;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceCommand>
 */
class DeviceCommandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'command' => fake()->randomElement(['on', 'off']),
            'status' => fake()->randomElement(['pending', 'executed']),
        ];
    }
}
