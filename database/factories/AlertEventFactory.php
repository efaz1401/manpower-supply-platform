<?php

namespace Database\Factories;

use App\Models\AlertEvent;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlertEvent>
 */
class AlertEventFactory extends Factory
{
    protected $model = AlertEvent::class;

    public function definition(): array
    {
        $days = fake()->numberBetween(-10, 90);

        return [
            'alertable_type' => Document::class,
            'alertable_id' => Document::factory(),
            'category' => 'document_expiry',
            'days_remaining' => $days,
            'threshold' => fake()->randomElement([90, 60, 30, 14, 7, 0]),
            'severity' => $days <= 7 ? 'critical' : ($days <= 30 ? 'warning' : 'info'),
            'title_en' => 'Document expiring soon',
            'title_ar' => 'وثيقة قرب الانتهاء',
            'due_date' => now()->addDays($days),
            'is_resolved' => false,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn () => ['is_resolved' => true, 'resolved_at' => now()]);
    }
}
