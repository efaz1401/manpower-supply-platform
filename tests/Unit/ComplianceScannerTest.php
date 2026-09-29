<?php

namespace Tests\Unit;

use App\Services\ComplianceScanner;
use PHPUnit\Framework\TestCase;

class ComplianceScannerTest extends TestCase
{
    private ComplianceScanner $scanner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scanner = new ComplianceScanner;
    }

    public function test_status_mapping_by_days_remaining(): void
    {
        $this->assertSame('expired', $this->scanner->statusForDays(-1));
        $this->assertSame('expiring', $this->scanner->statusForDays(0));
        $this->assertSame('expiring', $this->scanner->statusForDays(90));
        $this->assertSame('valid', $this->scanner->statusForDays(91));
    }

    public function test_crossed_threshold_returns_tightest_applicable(): void
    {
        $thresholds = [90, 60, 30, 14, 7, 0];

        // 25 days out: crosses 90,60,30 → tightest is 30
        $this->assertSame(30, $this->scanner->crossedThreshold(25, $thresholds));
        // 5 days out: tightest is 7
        $this->assertSame(7, $this->scanner->crossedThreshold(5, $thresholds));
        // 120 days out: none crossed
        $this->assertNull($this->scanner->crossedThreshold(120, $thresholds));
        // already expired: crosses 0
        $this->assertSame(0, $this->scanner->crossedThreshold(-3, $thresholds));
    }

    public function test_null_thresholds_fall_back_to_defaults(): void
    {
        $this->assertSame(30, $this->scanner->crossedThreshold(25, null));
    }
}
