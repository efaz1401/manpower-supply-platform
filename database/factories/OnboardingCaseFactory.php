<?php

namespace Database\Factories;

use App\Models\OnboardingCase;
use App\Models\RecruitmentCandidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OnboardingCase>
 */
class OnboardingCaseFactory extends Factory
{
    protected $model = OnboardingCase::class;

    public function definition(): array
    {
        return [
            'recruitment_candidate_id' => RecruitmentCandidate::factory(),
            'checklist' => [
                'visa' => false,
                'medical' => false,
                'contract' => false,
                'iqama' => false,
                'bank_account' => false,
            ],
            'status' => 'open',
            'target_date' => now()->addMonth(),
        ];
    }
}
