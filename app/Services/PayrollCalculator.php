<?php

namespace App\Services;

use App\Models\PayrollLine;
use App\Models\PayrollRun;
use App\Models\Timesheet;
use App\Models\Worker;

/**
 * Builds payroll lines for a payroll run. Net pay is
 * basic + allowances + overtime − deductions; a line is flagged when its
 * basic salary diverges from the worker's Qiwa-registered contract salary.
 */
class PayrollCalculator
{
    /** Compute the net pay for a set of components. */
    public function netPay(float $basic, float $allowances, float $overtime, float $deductions): float
    {
        return round($basic + $allowances + $overtime - $deductions, 2);
    }

    /** Whether the paid basic salary diverges from the Qiwa contract salary. */
    public function isSalaryMismatch(float $basic, float $qiwaSalary): bool
    {
        return round($basic, 2) !== round($qiwaSalary, 2);
    }

    /**
     * Generate (or refresh) payroll lines for every worker with an active
     * deployment in the run's period. Overtime is valued from verified
     * timesheets in the period at 1.5× the hourly rate derived from basic pay.
     */
    public function generate(PayrollRun $run): int
    {
        $workers = Worker::whereHas('deployments', fn ($q) => $q->where('status', 'active'))->get();

        foreach ($workers as $worker) {
            $overtimeHours = (float) Timesheet::where('worker_id', $worker->id)
                ->whereBetween('work_date', [$run->period_start, $run->period_end])
                ->where('status', 'verified')
                ->sum('overtime_hours');

            $basic = (float) $worker->basic_salary;
            $hourly = $basic > 0 ? $basic / 208 : 0; // 208 = standard monthly hours
            $overtimeAmount = round($overtimeHours * $hourly * 1.5, 2);
            $net = $this->netPay($basic, 0, $overtimeAmount, 0);

            PayrollLine::updateOrCreate(
                ['payroll_run_id' => $run->id, 'worker_id' => $worker->id],
                [
                    'basic_salary' => $basic,
                    'allowances' => 0,
                    'overtime_amount' => $overtimeAmount,
                    'deductions' => 0,
                    'net_pay' => $net,
                    'qiwa_contract_salary' => $worker->qiwa_contract_salary,
                    'salary_mismatch' => $this->isSalaryMismatch($basic, (float) $worker->qiwa_contract_salary),
                    'payment_method' => $worker->payment_method,
                    'iban' => $worker->iban,
                    'status' => 'pending',
                ],
            );
        }

        $run->update(['status' => 'calculated']);

        return $workers->count();
    }
}
