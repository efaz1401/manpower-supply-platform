<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Document extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'documentable_type', 'documentable_id', 'type', 'number', 'authority',
        'issue_date', 'expiry_date', 'file_path', 'status', 'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges();
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Days until expiry (negative if already expired). */
    public function daysRemaining(): ?int
    {
        return $this->expiry_date ? now()->startOfDay()->diffInDays($this->expiry_date->startOfDay(), false) : null;
    }
}
