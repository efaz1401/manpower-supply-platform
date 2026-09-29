<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Deployment;
use App\Models\Worker;
use App\Services\InvoiceBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_invoice_with_lines_vat_and_qr(): void
    {
        $client = Client::factory()->create(['payment_terms_days' => 30, 'vat_number' => '311111111100003']);
        $worker = Worker::factory()->create();
        Deployment::factory()->for($client)->for($worker)->create([
            'status' => 'active',
            'billing_rate' => 3500,
        ]);

        $invoice = app(InvoiceBuilder::class)->buildForClient(
            $client,
            now()->startOfMonth(),
            now()->endOfMonth(),
        );

        $this->assertNotNull($invoice);
        $this->assertCount(1, $invoice->lines);
        $this->assertEquals(3500, $invoice->subtotal);
        $this->assertEquals(525, $invoice->vat_amount);  // 15% of 3500
        $this->assertEquals(4025, $invoice->total);
        $this->assertNotEmpty($invoice->qr_payload);
        // QR payload is valid base64.
        $this->assertNotFalse(base64_decode($invoice->qr_payload, true));
    }

    public function test_returns_null_when_no_active_deployments(): void
    {
        $client = Client::factory()->create();

        $invoice = app(InvoiceBuilder::class)->buildForClient(
            $client,
            now()->startOfMonth(),
            now()->endOfMonth(),
        );

        $this->assertNull($invoice);
    }
}
