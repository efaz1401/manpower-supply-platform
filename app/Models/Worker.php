<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Worker extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'employee_no', 'name_en', 'name_ar', 'nationality', 'date_of_birth', 'gender',
        'profession', 'skills', 'iqama_number', 'passport_number', 'sponsorship_status',
        'availability', 'payment_method', 'bank_name', 'iban', 'qiwa_contract_salary',
        'basic_salary', 'accommodation', 'emergency_contact_name', 'emergency_contact_phone',
        'phone', 'notes',
    ];

    protected $casts = [
        'skills' => 'array',
        'date_of_birth' => 'date',
        'qiwa_contract_salary' => 'decimal:2',
        'basic_salary' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges();
    }

    public function name(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    public function currentDeployment(): HasOne
    {
        return $this->hasOne(Deployment::class)->where('status', 'active')->latestOfMany();
    }

    public function ajeerPermits(): HasMany
    {
        return $this->hasMany(AjeerPermit::class);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    public function payrollLines(): HasMany
    {
        return $this->hasMany(PayrollLine::class);
    }
}
