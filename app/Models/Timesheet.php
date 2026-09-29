<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id', 'deployment_id', 'shift_id', 'work_date', 'clock_in', 'clock_out',
        'hours', 'overtime_hours', 'clock_in_lat', 'clock_in_lng', 'selfie_path',
        'status', 'client_approved_by', 'verified_by', 'notes',
    ];

    protected $casts = [
        'work_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function clientApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_approved_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
