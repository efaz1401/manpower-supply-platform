<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\SupplyContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplyContract>
 */
class SupplyContractFactory extends Factory
{
    protected $model = SupplyContract::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'reference' => 'SC-'.fake()->unique()->numerify('####'),
            'title_en' => 'Master Supply Agreement',
            'title_ar' => 'اتفاقية توريد رئيسية',
            'professions' => ['Mason', 'Electrician', 'Driver', 'Cleaner'],
            'headcount_quota' => fake()->numberBetween(10, 50),
            'start_date' => now()->subMonths(6),
            'end_date' => now()->addMonths(6),
            'auto_renew' => fake()->boolean(),
            'status' => 'active',
        ];
    }
}
