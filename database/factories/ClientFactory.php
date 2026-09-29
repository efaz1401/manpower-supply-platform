<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name_en' => $name,
            'name_ar' => 'شركة '.fake()->lastName(),
            'cr_number' => 'CR-'.fake()->numerify('##########'),
            'establishment_id' => fake()->numerify('#-#######'),
            'vat_number' => fake()->numerify('3############003'),
            'contact_person' => fake()->name(),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => '+9665'.fake()->numerify('########'),
            'city' => 'Riyadh',
            'address' => fake()->streetAddress(),
            'payment_terms_days' => fake()->randomElement([30, 45, 60]),
            'status' => 'active',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}
