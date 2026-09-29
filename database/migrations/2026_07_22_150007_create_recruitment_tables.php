<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->string('nationality')->nullable();
            $table->string('profession')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('source')->nullable();
            // Pipeline stages.
            $table->enum('stage', [
                'sourced', 'offered', 'visa', 'contract',
                'medical', 'travel', 'arrived', 'onboarded', 'rejected',
            ])->default('sourced');
            $table->foreignId('converted_worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('stage');
        });

        Schema::create('onboarding_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_candidate_id')->constrained()->cascadeOnDelete();
            $table->json('checklist')->nullable(); // per-stage checklist state
            $table->enum('status', ['open', 'completed', 'cancelled'])->default('open');
            $table->date('target_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_cases');
        Schema::dropIfExists('recruitment_candidates');
    }
};
