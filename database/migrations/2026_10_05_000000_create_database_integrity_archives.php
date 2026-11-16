<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('database_integrity_archives')) {
            Schema::create('database_integrity_archives', function (Blueprint $table) {
                $table->id();
                $table->string('source_table', 64);
                $table->unsignedBigInteger('source_id');
                $table->string('reason');
                $table->json('original_record');
                $table->timestamp('archived_at');
                $table->unique(['source_table', 'source_id']);
            });
        }
    }

    public function down(): void
    {
        if (DB::table('database_integrity_archives')->exists()) {
            throw new RuntimeException('Cannot drop archived records. Restore or export them first.');
        }
        Schema::dropIfExists('database_integrity_archives');
    }
};
