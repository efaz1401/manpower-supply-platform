<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shift>
 */
class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => fake()->randomElement(['Day Shift', 'Night Shift', 'Morning Shift']),
            'site' => 'Site '.fake()->randomLetter(),
            'start_time' => '08:00',
            'end_time' => '17:00',
            'weekdays' => [0, 1, 2, 3, 4], // Sun-Thu (KSA work week)
        ];
    }
}
