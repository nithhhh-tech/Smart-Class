<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Room;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $room = Room::factory();
        return [
            'room_id' => $room,
            'device_id' => Device::factory(['room_id' => $room]),
            'action' => fake()->randomElement(['on', 'off']),
            'run_at' => fake()->time('H:i'),
            'days' => 'mon,tue,wed,thu,fri',
            'is_active' => true,
        ];
    }
}
