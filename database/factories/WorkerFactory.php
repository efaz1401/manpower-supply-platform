<?php

namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Worker>
 */
class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition(): array
    {
        $profession = fake()->randomElement(['Mason', 'Electrician', 'Driver', 'Cleaner']);
        $salary = ['Mason' => 3000, 'Electrician' => 3600, 'Driver' => 2600, 'Cleaner' => 2200][$profession];

        return [
            'employee_no' => 'EMP-'.fake()->unique()->numerify('####'),
            'name_en' => fake()->name('male'),
            'name_ar' => fake()->firstName().' '.fake()->lastName(),
            'nationality' => fake()->randomElement(['Pakistani', 'Indian', 'Bangladeshi', 'Nepali']),
            'date_of_birth' => fake()->dateTimeBetween('-55 years', '-22 years'),
            'gender' => 'male',
            'profession' => $profession,
            'skills' => fake()->randomElements(['welding', 'plumbing', 'wiring', 'driving', 'painting'], 2),
            'iqama_number' => '2'.fake()->unique()->numerify('#########'),
            'passport_number' => 'P'.fake()->numerify('#######'),
            'sponsorship_status' => 'sponsored',
            'availability' => fake()->randomElement(['available', 'deployed', 'on_leave']),
            'payment_method' => 'bank_transfer',
            'bank_name' => 'Al Rajhi Bank',
            'iban' => 'SA'.fake()->numerify('######################'),
            'qiwa_contract_salary' => $salary,
            'basic_salary' => $salary,
            'phone' => '+9665'.fake()->numerify('#######'),
        ];
    }

    public function available(): static
    {
        return $this->state(fn () => ['availability' => 'available']);
    }

    public function deployed(): static
    {
        return $this->state(fn () => ['availability' => 'deployed']);
    }
}
