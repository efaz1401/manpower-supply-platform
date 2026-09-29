<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Deployment;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deployment>
 */
class DeploymentFactory extends Factory
{
    protected $model = Deployment::class;

    public function definition(): array
    {
        return [
            'worker_id' => Worker::factory(),
            'client_id' => Client::factory(),
            'supply_contract_id' => null,
            'ajeer_permit_id' => null,
            'profession' => fake()->randomElement(['Mason', 'Electrician', 'Driver', 'Cleaner']),
            'site' => 'Site '.fake()->randomLetter(),
            'start_date' => now()->subMonths(2),
            'end_date' => null,
            'billing_rate' => fake()->randomElement([2500, 3000, 3500, 4200]),
            'billing_cycle' => 'monthly',
            'status' => 'active',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function ended(): static
    {
        return $this->state(fn () => ['status' => 'ended', 'end_date' => now()]);
    }
}
