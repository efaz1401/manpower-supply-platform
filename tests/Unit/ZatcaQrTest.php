<?php

namespace Tests\Unit;

use App\Services\ZatcaQr;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class ZatcaQrTest extends TestCase
{
    private ZatcaQr $qr;

    protected function setUp(): void
    {
        parent::setUp();
        $this->qr = new ZatcaQr;
    }

    public function test_payload_is_valid_base64(): void
    {
        $payload = $this->qr->payload(
            'ASMC Manpower',
            '300000000000003',
            Carbon::parse('2026-07-25T14:30:00+03:00'),
            4025.00,
            525.00,
        );

        $this->assertNotEmpty($payload);
        $decoded = base64_decode($payload, true);
        $this->assertNotFalse($decoded);
    }

    public function test_payload_contains_tlv_fields(): void
    {
        $payload = $this->qr->payload(
            'ASMC Manpower',
            '300000000000003',
            Carbon::parse('2026-07-25T14:30:00+03:00'),
            1150.00,
            150.00,
        );

        $decoded = base64_decode($payload, true);

        // Tag 1: seller name
        $this->assertStringContainsString('ASMC Manpower', $decoded);
        // Tag 2: VAT number
        $this->assertStringContainsString('300000000000003', $decoded);
        // Tag 3: ISO 8601 timestamp
        $this->assertStringContainsString('2026-07-25T14:30:00+03:00', $decoded);
        // Tag 4: total
        $this->assertStringContainsString('1150.00', $decoded);
        // Tag 5: VAT
        $this->assertStringContainsString('150.00', $decoded);
    }

    public function test_payload_changes_with_input(): void
    {
        $p1 = $this->qr->payload('A', '1', Carbon::parse('2026-01-01T00:00:00+00:00'), 100.00, 15.00);
        $p2 = $this->qr->payload('B', '2', Carbon::parse('2026-01-02T00:00:00+00:00'), 200.00, 30.00);

        $this->assertNotSame($p1, $p2);
    }
}
