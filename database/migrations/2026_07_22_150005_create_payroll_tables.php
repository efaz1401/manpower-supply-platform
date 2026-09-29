<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->date('period_start');
            $table->date('period_end');
            // WPS: salaries must be paid within 10 days of period end.
            $table->date('wps_deadline')->nullable();
            $table->enum('status', ['draft', 'calculated', 'approved', 'disbursed', 'closed'])->default('draft');
            $table->string('wps_file_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['period_year', 'period_month']);
        });

        Schema::create('payroll_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained()->cascadeOnDelete();
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('overtime_amount', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);
            $table->decimal('qiwa_contract_salary', 12, 2)->default(0);
            // Flag raised when net/basic diverges from the Qiwa contract salary.
            $table->boolean('salary_mismatch')->default(false);
            $table->enum('payment_method', ['bank_transfer', 'payroll_card'])->default('bank_transfer');
            $table->string('iban')->nullable();
            $table->enum('status', ['pending', 'disbursed', 'failed'])->default('pending');
            $table->dateTime('disbursed_at')->nullable();
            $table->string('payslip_path')->nullable();
            $table->timestamps();

            $table->index(['payroll_run_id', 'worker_id']);
            $table->index('salary_mismatch');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_lines');
        Schema::dropIfExists('payroll_runs');
    }
};
