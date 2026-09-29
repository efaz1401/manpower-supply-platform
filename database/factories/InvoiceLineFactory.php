<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceLine>
 */
class InvoiceLineFactory extends Factory
{
    protected $model = InvoiceLine::class;

    public function definition(): array
    {
        $qty = 1;
        $price = fake()->randomElement([2500, 3000, 3500, 4200]);

        return [
            'invoice_id' => Invoice::factory(),
            'worker_id' => null,
            'deployment_id' => null,
            'description' => fake()->randomElement(['Mason', 'Electrician', 'Driver', 'Cleaner']).' — '.fake()->name(),
            'quantity' => $qty,
            'unit' => 'month',
            'unit_price' => $price,
            'line_total' => $qty * $price,
        ];
    }
}
