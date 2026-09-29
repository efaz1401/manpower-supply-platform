<?php

namespace Tests\Feature;

use App\Models\AlertEvent;
use App\Models\Document;
use App\Models\Worker;
use App\Services\ComplianceScanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplianceScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_flips_document_status_and_raises_alert(): void
    {
        $worker = Worker::factory()->create();
        $document = Document::factory()->for($worker, 'documentable')->create([
            'type' => 'iqama',
            'expiry_date' => now()->addDays(5),
            'status' => 'valid',
        ]);

        app(ComplianceScanner::class)->scan();

        $this->assertSame('expiring', $document->fresh()->status);
        $this->assertDatabaseHas('alert_events', [
            'alertable_type' => Document::class,
            'alertable_id' => $document->id,
            'is_resolved' => false,
        ]);
    }

    public function test_expired_document_gets_expired_status(): void
    {
        $worker = Worker::factory()->create();
        $document = Document::factory()->for($worker, 'documentable')->create([
            'expiry_date' => now()->subDays(3),
            'status' => 'valid',
        ]);

        app(ComplianceScanner::class)->scan();

        $this->assertSame('expired', $document->fresh()->status);
    }

    public function test_scan_is_idempotent_for_alerts(): void
    {
        $worker = Worker::factory()->create();
        Document::factory()->for($worker, 'documentable')->create([
            'expiry_date' => now()->addDays(5),
        ]);

        app(ComplianceScanner::class)->scan();
        app(ComplianceScanner::class)->scan();

        // The same threshold crossing must not create duplicate unresolved alerts.
        $this->assertSame(1, AlertEvent::where('is_resolved', false)->count());
    }
}
