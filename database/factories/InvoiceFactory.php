<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->randomElement([7000, 10500, 14000]);
        $vat = round($subtotal * 0.15, 2);

        return [
            'invoice_number' => 'INV-'.now()->format('Ym').'-'.fake()->unique()->numerify('###'),
            'client_id' => Client::factory(),
            'issue_date' => now()->startOfMonth(),
            'due_date' => now()->startOfMonth()->addDays(30),
            'period_start' => now()->subMonth()->startOfMonth(),
            'period_end' => now()->subMonth()->endOfMonth(),
            'subtotal' => $subtotal,
            'vat_rate' => 15,
            'vat_amount' => $vat,
            'total' => $subtotal + $vat,
            'amount_paid' => 0,
            'currency' => 'SAR',
            'status' => 'issued',
            'seller_vat_number' => '300000000000003',
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'paid',
            'amount_paid' => $attrs['total'] ?? 0,
        ]);
    }
}
