<?php

namespace Database\Factories;

use App\Models\RecruitmentCandidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecruitmentCandidate>
 */
class RecruitmentCandidateFactory extends Factory
{
    protected $model = RecruitmentCandidate::class;

    public function definition(): array
    {
        return [
            'name_en' => fake()->name('male'),
            'name_ar' => fake()->firstName().' '.fake()->lastName(),
            'nationality' => fake()->randomElement(['Pakistani', 'Indian', 'Bangladeshi', 'Nepali']),
            'profession' => fake()->randomElement(['Mason', 'Electrician', 'Driver', 'Cleaner']),
            'phone' => '+9665'.fake()->numerify('#######'),
            'email' => fake()->safeEmail(),
            'source' => fake()->randomElement(['agency', 'referral', 'direct']),
            'stage' => fake()->randomElement(['sourced', 'offered', 'visa', 'contract', 'medical', 'travel', 'arrived']),
        ];
    }

    public function stage(string $stage): static
    {
        return $this->state(fn () => ['stage' => $stage]);
    }
}
