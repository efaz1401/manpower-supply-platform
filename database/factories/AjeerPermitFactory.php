<?php

namespace Database\Factories;

use App\Models\AjeerPermit;
use App\Models\Client;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AjeerPermit>
 */
class AjeerPermitFactory extends Factory
{
    protected $model = AjeerPermit::class;

    public function definition(): array
    {
        return [
            'worker_id' => Worker::factory(),
            'client_id' => Client::factory(),
            'profession' => fake()->randomElement(['Mason', 'Electrician', 'Driver', 'Cleaner']),
            'permit_number' => 'AJ-'.fake()->unique()->numerify('######'),
            'issue_date' => now()->subMonths(3),
            'expiry_date' => now()->addMonths(9),
            'status' => 'active',
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
            'expiry_date' => now()->subDays(5),
        ]);
    }
}
