<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Builds a client invoice from their active deployments for a billing period:
 * one line per deployment at its monthly billing rate, then computes subtotal,
 * 15% KSA VAT, total, and a ZATCA Phase-1 QR payload.
 */
class InvoiceBuilder
{
    public const VAT_RATE = 15.0;

    public const SELLER_VAT_NUMBER = '300000000000003';

    public const SELLER_NAME = 'ASMC Manpower';

    public function __construct(private ZatcaQr $qr) {}

    /**
     * Create an issued invoice for a client covering the given period.
     * Returns null when the client has no active deployments to bill.
     */
    public function buildForClient(Client $client, Carbon $periodStart, Carbon $periodEnd): ?Invoice
    {
        $deployments = $client->deployments()->with('worker')->where('status', 'active')->get();

        if ($deployments->isEmpty()) {
            return null;
        }

        return DB::transaction(function () use ($client, $deployments, $periodStart, $periodEnd) {
            $issueDate = now();

            $invoice = Invoice::create([
                'invoice_number' => $this->nextInvoiceNumber(),
                'client_id' => $client->id,
                'issue_date' => $issueDate,
                'due_date' => $issueDate->copy()->addDays($client->payment_terms_days ?? 30),
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'vat_rate' => self::VAT_RATE,
                'currency' => 'SAR',
                'status' => 'issued',
                'seller_vat_number' => self::SELLER_VAT_NUMBER,
                'buyer_vat_number' => $client->vat_number,
            ]);

            $subtotal = 0;
            foreach ($deployments as $deployment) {
                $rate = (float) $deployment->billing_rate;
                $subtotal += $rate;
                $invoice->lines()->create([
                    'worker_id' => $deployment->worker_id,
                    'deployment_id' => $deployment->id,
                    'description' => $deployment->profession.' — '.($deployment->worker?->name_en ?? ''),
                    'quantity' => 1,
                    'unit' => 'month',
                    'unit_price' => $rate,
                    'line_total' => $rate,
                ]);
            }

            $this->finalize($invoice, $subtotal);

            return $invoice->fresh('lines');
        });
    }

    /** Compute totals + QR for an invoice given its subtotal, and persist. */
    public function finalize(Invoice $invoice, float $subtotal): Invoice
    {
        $vat = round($subtotal * self::VAT_RATE / 100, 2);
        $total = round($subtotal + $vat, 2);

        $invoice->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vat,
            'total' => $total,
            'qr_payload' => $this->qr->payload(
                self::SELLER_NAME,
                self::SELLER_VAT_NUMBER,
                $invoice->issue_date ?? now(),
                $total,
                $vat,
            ),
        ]);

        return $invoice;
    }

    /** Sequential invoice number scoped to the current year-month. */
    private function nextInvoiceNumber(): string
    {
        $prefix = 'INV-'.now()->format('Ym').'-';
        $count = Invoice::where('invoice_number', 'like', $prefix.'%')->count();

        return $prefix.str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);
    }
}
