<?php

namespace Database\Factories;

use App\Models\PayrollLine;
use App\Models\PayrollRun;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollLine>
 */
class PayrollLineFactory extends Factory
{
    protected $model = PayrollLine::class;

    public function definition(): array
    {
        $basic = fake()->randomElement([2200, 2600, 3000, 3600]);
        $overtime = fake()->randomElement([0, 150, 250]);

        return [
            'payroll_run_id' => PayrollRun::factory(),
            'worker_id' => Worker::factory(),
            'basic_salary' => $basic,
            'allowances' => 0,
            'overtime_amount' => $overtime,
            'deductions' => 0,
            'net_pay' => $basic + $overtime,
            'qiwa_contract_salary' => $basic,
            'salary_mismatch' => false,
            'payment_method' => 'bank_transfer',
            'iban' => 'SA'.fake()->numerify('######################'),
            'status' => 'pending',
        ];
    }
}
