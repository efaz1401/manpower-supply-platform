<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajeer permits gate deployment (worker + client + profession, max 3 years).
        Schema::create('ajeer_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('profession');
            $table->string('permit_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable()->index();
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['worker_id', 'client_id', 'status']);
        });

        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supply_contract_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ajeer_permit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('profession');          // validated against permit + worker profession
            $table->string('site')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('billing_rate', 12, 2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'hourly'])->default('monthly');
            // pending -> permit_issued -> active -> ended
            $table->enum('status', ['pending', 'permit_issued', 'active', 'ended'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['worker_id', 'status']);
            $table->index(['client_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
        Schema::dropIfExists('ajeer_permits');
    }
};
