<?php

namespace Database\Factories;

use App\Models\Deployment;
use App\Models\Timesheet;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Timesheet>
 */
class TimesheetFactory extends Factory
{
    protected $model = Timesheet::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-10 days', 'now');

        return [
            'worker_id' => Worker::factory(),
            'deployment_id' => Deployment::factory(),
            'shift_id' => null,
            'work_date' => $date,
            'clock_in' => (clone $date)->setTime(8, 0),
            'clock_out' => (clone $date)->setTime(17, 0),
            'hours' => 8,
            'overtime_hours' => 0,
            'status' => 'submitted',
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => ['status' => 'verified']);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
