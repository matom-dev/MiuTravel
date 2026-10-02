<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vnpay_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_tour_id')->index();
            $table->string('reference', 32)->unique();
            $table->unsignedBigInteger('amount'); // VND, before multiplication by 100
            $table->string('status')->default('pending');
            $table->string('transaction_no')->nullable()->unique();
            $table->string('response_code', 2)->nullable();
            $table->string('bank_code')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vnpay_payments');
    }
};
