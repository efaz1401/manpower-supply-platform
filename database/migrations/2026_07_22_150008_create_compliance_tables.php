<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Configurable thresholds for the expiry / compliance engine.
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('document_type');            // matches documents.type or 'contract','ajeer_permit'
            $table->json('thresholds')->nullable();      // e.g. [90,60,30,14,7,0]
            $table->boolean('email_enabled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('document_type');
        });

        // Individual alert instances produced by scans.
        Schema::create('alert_events', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('alertable'); // e.g. Document, AjeerPermit, SupplyContract
            $table->string('category');          // document_expiry, contract_renewal, wps_deadline, ...
            $table->smallInteger('days_remaining')->nullable();
            $table->unsignedSmallInteger('threshold')->nullable();
            $table->enum('severity', ['info', 'warning', 'critical'])->default('warning');
            $table->string('title_en');
            $table->string('title_ar');
            $table->text('message_en')->nullable();
            $table->text('message_ar')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'is_resolved']);
            $table->index('due_date');
        });

        // Laravel database notifications (in-app notification centre).
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('alert_events');
        Schema::dropIfExists('alert_rules');
    }
};
