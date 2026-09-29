<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('employee_no')->nullable()->unique();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('nationality')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('profession');                      // licensed / primary trade
            $table->json('skills')->nullable();
            $table->string('iqama_number')->nullable()->index();
            $table->string('passport_number')->nullable();
            $table->enum('sponsorship_status', ['sponsored', 'transferred_out', 'exited'])->default('sponsored');
            // Availability lifecycle used across deployment & scheduling.
            $table->enum('availability', ['available', 'deployed', 'on_leave', 'exiting'])->default('available');
            // Payroll / WPS details (bank transfer or payroll card only).
            $table->enum('payment_method', ['bank_transfer', 'payroll_card'])->default('bank_transfer');
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->decimal('qiwa_contract_salary', 12, 2)->default(0); // Qiwa registered salary
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->string('accommodation')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name_en');
            $table->index('profession');
            $table->index('availability');
            $table->index('nationality');
        });

        // Polymorphic document vault (workers, clients, contracts, ...).
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable');
            $table->enum('type', [
                'iqama', 'passport', 'work_permit', 'ajeer_permit',
                'medical', 'insurance', 'license', 'contract', 'other',
            ]);
            $table->string('number')->nullable();
            $table->string('authority')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable()->index();
            $table->string('file_path')->nullable();
            // Denormalised status maintained by the compliance engine.
            $table->enum('status', ['valid', 'expiring', 'expired', 'missing'])->default('valid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('workers');
    }
};
