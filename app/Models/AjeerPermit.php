<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class AjeerPermit extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'worker_id', 'client_id', 'profession', 'permit_number',
        'issue_date', 'expiry_date', 'status', 'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges();
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** A permit is valid if active and not past its expiry date. */
    public function isValid(): bool
    {
        return $this->status === 'active'
            && $this->expiry_date
            && $this->expiry_date->endOfDay()->isFuture();
    }
}
