<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_candidate_id', 'checklist', 'status', 'target_date',
    ];

    protected $casts = [
        'checklist' => 'array',
        'target_date' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCandidate::class, 'recruitment_candidate_id');
    }
}
