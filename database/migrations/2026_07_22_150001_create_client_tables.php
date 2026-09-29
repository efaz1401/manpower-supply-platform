<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('cr_number')->nullable();          // Commercial Registration
            $table->string('establishment_id')->nullable();   // MOL / Qiwa establishment (manual)
            $table->string('vat_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->unsignedSmallInteger('payment_terms_days')->default(30);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name_en');
            $table->index('status');
        });

        // Master supply agreements between us and a client.
        Schema::create('supply_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->json('professions')->nullable();       // scoped professions
            $table->unsignedInteger('headcount_quota')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['client_id', 'status']);
            $table->index('end_date');
        });

        // Rate card: price per profession per client (billing rate to the client).
        Schema::create('rate_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supply_contract_id')->nullable()->constrained()->nullOnDelete();
            $table->string('profession');
            $table->decimal('monthly_rate', 12, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('overtime_rate', 10, 2)->default(0);
            $table->string('currency', 3)->default('SAR');
            $table->timestamps();

            $table->index(['client_id', 'profession']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_cards');
        Schema::dropIfExists('supply_contracts');
        Schema::dropIfExists('clients');
    }
};
