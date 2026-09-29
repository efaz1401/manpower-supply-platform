<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AlertEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'alertable_type', 'alertable_id', 'category', 'days_remaining', 'threshold',
        'severity', 'title_en', 'title_ar', 'message_en', 'message_ar',
        'due_date', 'is_resolved', 'resolved_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function alertable(): MorphTo
    {
        return $this->morphTo();
    }

    public function title(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function message(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->message_ar : $this->message_en;
    }
}
