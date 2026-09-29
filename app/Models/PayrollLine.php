<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PayrollLine extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'payroll_run_id', 'worker_id', 'basic_salary', 'allowances', 'overtime_amount',
        'deductions', 'net_pay', 'qiwa_contract_salary', 'salary_mismatch',
        'payment_method', 'iban', 'status', 'disbursed_at', 'payslip_path',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'qiwa_contract_salary' => 'decimal:2',
        'salary_mismatch' => 'boolean',
        'disbursed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges();
    }

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }
}
