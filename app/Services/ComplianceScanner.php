<?php

namespace App\Services;

use App\Models\AjeerPermit;
use App\Models\AlertEvent;
use App\Models\AlertRule;
use App\Models\Document;
use Illuminate\Support\Carbon;

/**
 * Scans expiry-bearing records (documents, Ajeer permits) against the
 * configured AlertRule thresholds. It keeps each Document's denormalised
 * status column current (valid → expiring → expired) and raises an
 * AlertEvent when a record crosses a configured threshold, resolving stale
 * alerts once a record is renewed.
 */
class ComplianceScanner
{
    /** Days-out at or below which a document is considered "expiring". */
    public const EXPIRING_WINDOW_DAYS = 90;

    /**
     * Run a full scan. Returns a summary
     * ['documents' => int, 'alerts_created' => int, 'alerts_resolved' => int].
     */
    public function scan(?Carbon $asOf = null): array
    {
        $asOf = ($asOf ?? now())->startOfDay();
        $rules = AlertRule::where('is_active', true)->get()->keyBy('document_type');

        $created = 0;
        $documents = 0;

        foreach (Document::whereNotNull('expiry_date')->cursor() as $document) {
            $documents++;
            $days = $this->daysBetween($asOf, $document->expiry_date);

            $newStatus = $this->statusForDays($days);
            if ($document->status !== $newStatus) {
                $document->update(['status' => $newStatus]);
            }

            $rule = $rules->get($document->type);
            $threshold = $this->crossedThreshold($days, $rule?->thresholds);
            if ($threshold !== null && $this->raiseAlert($document, $document->type, $days, $threshold, $asOf)) {
                $created++;
            }
        }

        foreach (AjeerPermit::whereNotNull('expiry_date')->where('status', '!=', 'cancelled')->cursor() as $permit) {
            $days = $this->daysBetween($asOf, $permit->expiry_date);
            if ($days < 0 && $permit->status !== 'expired') {
                $permit->update(['status' => 'expired']);
            }
            $rule = $rules->get('ajeer_permit');
            $threshold = $this->crossedThreshold($days, $rule?->thresholds);
            if ($threshold !== null && $this->raiseAlert($permit, 'ajeer_permit', $days, $threshold, $asOf)) {
                $created++;
            }
        }

        $resolved = $this->resolveRenewed($asOf);

        return ['documents' => $documents, 'alerts_created' => $created, 'alerts_resolved' => $resolved];
    }

    /** Map days-remaining to a Document status value. */
    public function statusForDays(int $days): string
    {
        return match (true) {
            $days < 0 => 'expired',
            $days <= self::EXPIRING_WINDOW_DAYS => 'expiring',
            default => 'valid',
        };
    }

    /**
     * The largest configured threshold the record has crossed (days <= threshold),
     * or null if none apply. Thresholds default to [90,60,30,14,7,0].
     */
    public function crossedThreshold(int $days, ?array $thresholds): ?int
    {
        $thresholds ??= [90, 60, 30, 14, 7, 0];
        sort($thresholds); // ascending

        $crossed = null;
        foreach ($thresholds as $t) {
            if ($days <= $t) {
                $crossed = $crossed === null ? $t : min($crossed, $t);
            }
        }

        return $crossed;
    }

    /** Severity from days remaining. */
    private function severity(int $days): string
    {
        return match (true) {
            $days <= 7 => 'critical',
            $days <= 30 => 'warning',
            default => 'info',
        };
    }

    /**
     * Create an alert for a record+threshold pair unless an unresolved one
     * already exists for it. Returns true when a new alert was created.
     */
    private function raiseAlert($model, string $category, int $days, int $threshold, Carbon $asOf): bool
    {
        $exists = AlertEvent::where('alertable_type', $model::class)
            ->where('alertable_id', $model->id)
            ->where('threshold', $threshold)
            ->where('is_resolved', false)
            ->exists();

        if ($exists) {
            return false;
        }

        AlertEvent::create([
            'alertable_type' => $model::class,
            'alertable_id' => $model->id,
            'category' => $days < 0 ? 'expired' : 'document_expiry',
            'days_remaining' => $days,
            'threshold' => $threshold,
            'severity' => $this->severity($days),
            'title_en' => $days < 0 ? 'Document expired' : 'Document expiring soon',
            'title_ar' => $days < 0 ? 'وثيقة منتهية' : 'وثيقة قرب الانتهاء',
            'message_en' => ucfirst(str_replace('_', ' ', $category)).' — '.abs($days).' days '.($days < 0 ? 'overdue' : 'remaining'),
            'message_ar' => 'المتبقي '.abs($days).' يوماً',
            'due_date' => $model->expiry_date,
            'is_resolved' => false,
        ]);

        return true;
    }

    /** Resolve alerts whose underlying record is no longer near expiry. */
    private function resolveRenewed(Carbon $asOf): int
    {
        $resolved = 0;

        foreach (AlertEvent::where('is_resolved', false)->whereNotNull('due_date')->cursor() as $alert) {
            $days = $this->daysBetween($asOf, Carbon::parse($alert->due_date));
            if ($days > self::EXPIRING_WINDOW_DAYS) {
                $alert->update(['is_resolved' => true, 'resolved_at' => now()]);
                $resolved++;
            }
        }

        return $resolved;
    }

    /** Whole-day signed difference from $asOf to $date (negative if past). */
    private function daysBetween(Carbon $asOf, $date): int
    {
        return (int) $asOf->diffInDays(Carbon::parse($date)->startOfDay(), false);
    }
}
