<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'documentable_type' => Worker::class,
            'documentable_id' => Worker::factory(),
            'type' => fake()->randomElement(['iqama', 'passport', 'work_permit', 'medical', 'insurance']),
            'number' => fake()->numerify('##########'),
            'authority' => fake()->randomElement(['MOI', 'Embassy', 'MOL']),
            'issue_date' => now()->subYear(),
            'expiry_date' => now()->addDays(fake()->numberBetween(-30, 400)),
            'status' => 'valid',
        ];
    }

    /** Document that expires in the given number of days (negative = already expired). */
    public function expiringIn(int $days): static
    {
        return $this->state(fn () => ['expiry_date' => now()->addDays($days)]);
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }
}
