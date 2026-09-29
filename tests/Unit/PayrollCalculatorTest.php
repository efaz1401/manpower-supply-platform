<?php

namespace Tests\Unit;

use App\Services\PayrollCalculator;
use PHPUnit\Framework\TestCase;

class PayrollCalculatorTest extends TestCase
{
    private PayrollCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new PayrollCalculator;
    }

    public function test_net_pay_is_basic_plus_additions_minus_deductions(): void
    {
        $this->assertSame(3350.0, $this->calc->netPay(3000, 200, 250, 100));
    }

    public function test_salary_mismatch_detected_when_basic_differs_from_qiwa(): void
    {
        $this->assertTrue($this->calc->isSalaryMismatch(2700, 3000));
        $this->assertFalse($this->calc->isSalaryMismatch(3000, 3000));
    }

    public function test_salary_mismatch_ignores_sub_cent_noise(): void
    {
        $this->assertFalse($this->calc->isSalaryMismatch(3000.001, 3000.0));
    }
}
