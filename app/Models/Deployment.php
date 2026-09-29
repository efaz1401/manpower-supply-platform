<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Deployment extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'worker_id', 'client_id', 'supply_contract_id', 'ajeer_permit_id',
        'profession', 'site', 'start_date', 'end_date', 'billing_rate',
        'billing_cycle', 'status', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'billing_rate' => 'decimal:2',
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

    public function supplyContract(): BelongsTo
    {
        return $this->belongsTo(SupplyContract::class);
    }

    public function ajeerPermit(): BelongsTo
    {
        return $this->belongsTo(AjeerPermit::class);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }
}
