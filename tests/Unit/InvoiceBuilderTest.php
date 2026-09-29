<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Deployment;
use App\Models\Worker;
use App\Services\InvoiceBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_invoice_with_vat_and_qr(): void
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
        $this->assertEquals(525, $invoice->vat_amount); // 15% of 3500
        $this->assertEquals(4025, $invoice->total);
        $this->assertNotEmpty($invoice->qr_payload);
        $this->assertNotFalse(base64_decode($invoice->qr_payload, true));
    }

    public function test_returns_null_when_client_has_no_active_deployments(): void
    {
        $client = Client::factory()->create();

        $invoice = app(InvoiceBuilder::class)->buildForClient(
            $client,
            now()->startOfMonth(),
            now()->endOfMonth(),
        );

        $this->assertNull($invoice);
    }

    public function test_invoice_number_follows_year_month_sequence(): void
    {
        $client = Client::factory()->create();
        $worker = Worker::factory()->create();
        Deployment::factory()->for($client)->for($worker)->create([
            'status' => 'active',
            'billing_rate' => 1000,
        ]);

        $builder = app(InvoiceBuilder::class);

        $first = $builder->buildForClient($client, now()->startOfMonth(), now()->endOfMonth());
        $second = $builder->buildForClient($client, now()->startOfMonth(), now()->endOfMonth());

        $this->assertStringStartsWith('INV-'.now()->format('Ym').'-', $first->invoice_number);
        $this->assertSame('001', substr($first->invoice_number, -3));
        $this->assertSame('002', substr($second->invoice_number, -3));
    }
}
