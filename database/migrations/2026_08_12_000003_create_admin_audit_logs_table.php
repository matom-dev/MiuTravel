<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_audit_logs')) {
            Schema::create('admin_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('actor_guard', 30)->default('admins');
                $table->string('action', 80);
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->json('metadata')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();

                $table->index(['action', 'created_at']);
                $table->index(['subject_type', 'subject_id']);
                $table->index(['actor_guard', 'actor_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_audit_logs');
    }
};
