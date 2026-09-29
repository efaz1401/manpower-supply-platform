<?php

namespace Tests\Unit;

use App\Services\TimesheetCalculator;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class TimesheetCalculatorTest extends TestCase
{
    private TimesheetCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new TimesheetCalculator;
    }

    public function test_standard_eight_hour_day_has_no_overtime(): void
    {
        $result = $this->calc->compute(
            Carbon::parse('2026-06-01 08:00'),
            Carbon::parse('2026-06-01 16:00'),
        );

        $this->assertSame(8.0, $result['hours']);
        $this->assertSame(0.0, $result['overtime_hours']);
    }

    public function test_time_beyond_standard_day_is_overtime(): void
    {
        $result = $this->calc->compute(
            Carbon::parse('2026-06-01 08:00'),
            Carbon::parse('2026-06-01 18:30'), // 10.5h worked
        );

        $this->assertSame(8.0, $result['hours']);
        $this->assertSame(2.5, $result['overtime_hours']);
    }

    public function test_missing_or_invalid_window_yields_zero(): void
    {
        $this->assertSame(
            ['hours' => 0.0, 'overtime_hours' => 0.0],
            $this->calc->compute(null, Carbon::parse('2026-06-01 16:00')),
        );

        // clock_out before clock_in
        $this->assertSame(
            ['hours' => 0.0, 'overtime_hours' => 0.0],
            $this->calc->compute(Carbon::parse('2026-06-01 16:00'), Carbon::parse('2026-06-01 08:00')),
        );
    }
}
