<?php

namespace App\Services;

use Carbon\CarbonInterface;

/**
 * Generates a ZATCA (Saudi e-invoicing) Phase-1 "simplified tax invoice" QR
 * payload: a base64-encoded TLV (Tag-Length-Value) string of five fields.
 *
 * Phase 1 requires only these five tags; cryptographic stamping (CSR, signed
 * hash, public key) is Phase 2 and intentionally out of scope here.
 */
class ZatcaQr
{
    /**
     * @param  string  $sellerName  Seller (our) legal name
     * @param  string  $vatNumber  Seller VAT registration number
     * @param  CarbonInterface  $timestamp  Invoice issue date/time (ISO 8601)
     * @param  float  $total  Invoice total incl. VAT
     * @param  float  $vat  VAT total
     */
    public function payload(string $sellerName, string $vatNumber, CarbonInterface $timestamp, float $total, float $vat): string
    {
        $tlv = $this->tlv(1, $sellerName)
            .$this->tlv(2, $vatNumber)
            .$this->tlv(3, $timestamp->toIso8601String())
            .$this->tlv(4, number_format($total, 2, '.', ''))
            .$this->tlv(5, number_format($vat, 2, '.', ''));

        return base64_encode($tlv);
    }

    /** Encode a single Tag-Length-Value triplet. */
    private function tlv(int $tag, string $value): string
    {
        return chr($tag).chr(strlen($value)).$value;
    }
}
