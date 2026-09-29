<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecruitmentCandidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en', 'name_ar', 'nationality', 'profession', 'phone', 'email',
        'source', 'stage', 'converted_worker_id', 'notes',
    ];

    public function onboardingCase(): HasOne
    {
        return $this->hasOne(OnboardingCase::class);
    }

    public function convertedWorker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'converted_worker_id');
    }

    /** Localised display name based on the active locale. */
    public function name(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar ? $this->name_ar : $this->name_en;
    }
}
