<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\RateCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RateCard>
 */
class RateCardFactory extends Factory
{
    protected $model = RateCard::class;

    public function definition(): array
    {
        $profession = fake()->randomElement(['Mason', 'Electrician', 'Driver', 'Cleaner']);
        $monthly = ['Mason' => 3500, 'Electrician' => 4200, 'Driver' => 3000, 'Cleaner' => 2500][$profession];

        return [
            'client_id' => Client::factory(),
            'supply_contract_id' => null,
            'profession' => $profession,
            'monthly_rate' => $monthly,
            'hourly_rate' => round($monthly / 208, 2),
            'overtime_rate' => round($monthly / 208 * 1.5, 2),
            'currency' => 'SAR',
        ];
    }
}
