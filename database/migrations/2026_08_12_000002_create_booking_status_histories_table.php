<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('booking_status_histories')) {
            Schema::create('booking_status_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('book_tour_id');
                $table->unsignedTinyInteger('old_status')->nullable();
                $table->unsignedTinyInteger('new_status');
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->string('changed_guard', 30)->nullable();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['book_tour_id', 'created_at']);
                $table->index(['new_status', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_status_histories');
    }
};
