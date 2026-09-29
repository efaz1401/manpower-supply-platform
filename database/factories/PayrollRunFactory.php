<?php

namespace Database\Factories;

use App\Models\PayrollRun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollRun>
 */
class PayrollRunFactory extends Factory
{
    protected $model = PayrollRun::class;

    public function definition(): array
    {
        $period = now()->subMonth();

        return [
            'reference' => 'PR-'.$period->format('Y-m'),
            'period_year' => $period->year,
            'period_month' => $period->month,
            'period_start' => $period->copy()->startOfMonth(),
            'period_end' => $period->copy()->endOfMonth(),
            'wps_deadline' => $period->copy()->endOfMonth()->addDays(10),
            'status' => 'draft',
        ];
    }

    public function calculated(): static
    {
        return $this->state(fn () => ['status' => 'calculated']);
    }
}
