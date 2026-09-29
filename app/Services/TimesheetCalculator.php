<?php

namespace App\Services;

use App\Models\Timesheet;
use Carbon\CarbonInterface;

/**
 * Derives worked hours and overtime for a timesheet from its clock in/out
 * timestamps. Anything worked beyond the standard day length counts as overtime.
 */
class TimesheetCalculator
{
    /** Standard paid hours in a work day; time beyond this is overtime. */
    public const STANDARD_DAY_HOURS = 8.0;

    /**
     * Compute ['hours' => float, 'overtime_hours' => float] for a clock window.
     * Returns zeroes when either bound is missing or the window is non-positive.
     */
    public function compute(?CarbonInterface $clockIn, ?CarbonInterface $clockOut, float $standardDay = self::STANDARD_DAY_HOURS): array
    {
        if (! $clockIn || ! $clockOut || $clockOut->lessThanOrEqualTo($clockIn)) {
            return ['hours' => 0.0, 'overtime_hours' => 0.0];
        }

        $worked = round($clockIn->diffInMinutes($clockOut) / 60, 2);
        $regular = min($worked, $standardDay);
        $overtime = round(max(0, $worked - $standardDay), 2);

        return ['hours' => $regular, 'overtime_hours' => $overtime];
    }

    /** Recompute and persist a timesheet's hours from its clock window. */
    public function recalculate(Timesheet $timesheet, float $standardDay = self::STANDARD_DAY_HOURS): Timesheet
    {
        $result = $this->compute($timesheet->clock_in, $timesheet->clock_out, $standardDay);
        $timesheet->fill($result)->save();

        return $timesheet;
    }
}
