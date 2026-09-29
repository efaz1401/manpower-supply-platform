<?php

namespace Database\Factories;

use App\Models\AlertRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlertRule>
 */
class AlertRuleFactory extends Factory
{
    protected $model = AlertRule::class;

    public function definition(): array
    {
        return [
            'document_type' => fake()->unique()->randomElement([
                'iqama', 'passport', 'work_permit', 'ajeer_permit', 'medical', 'insurance', 'license', 'contract',
            ]),
            'thresholds' => [90, 60, 30, 14, 7, 0],
            'email_enabled' => false,
            'is_active' => true,
        ];
    }
}
