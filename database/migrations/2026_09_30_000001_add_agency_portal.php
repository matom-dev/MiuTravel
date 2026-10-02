<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->unique()->constrained('users');
            $t->string('name');
            $t->string('logo')->nullable();
            $t->text('description')->nullable();
            $t->string('address')->nullable();
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->text('verification_information')->nullable();
            $t->string('verification_status')->default('pending');
            $t->boolean('active')->default(true);
            $t->unsignedInteger('commission_basis_points')->default(0);
            $t->timestamps();
        });
        Schema::table('tours', function (Blueprint $t) {
            $t->foreignId('agency_id')->nullable()->constrained('agencies');
            $t->text('cancellation_policy')->nullable();
        });
        Schema::table('tour_schedules', function (Blueprint $t) {
            $t->unsignedInteger('adult_price')->nullable();
            $t->unsignedInteger('child_price')->nullable();
            $t->date('registration_deadline')->nullable();
        });
        Schema::table('book_tours', function (Blueprint $t) {
            $t->text('policy_snapshot')->nullable();
            $t->unsignedInteger('commission_basis_points')->default(0);
            $t->timestamp('revenue_recognized_at')->nullable();
        });
        Schema::create('booking_passengers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('book_tour_id')->constrained('book_tours');
            $t->string('name');
            $t->string('attendance')->default('pending');
            $t->timestamps();
        });
        Schema::create('agency_transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('book_tour_id')->constrained('book_tours');
            $t->foreignId('recorded_by')->constrained('users');
            $t->string('type');
            $t->unsignedBigInteger('amount');
            $t->string('reference')->unique();
            $t->text('note');
            $t->timestamp('occurred_at');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_transactions');
        Schema::dropIfExists('booking_passengers');
        Schema::table('book_tours', fn (Blueprint $t) => $t->dropColumn(['policy_snapshot', 'commission_basis_points', 'revenue_recognized_at']));
        Schema::table('tour_schedules', fn (Blueprint $t) => $t->dropColumn(['adult_price', 'child_price', 'registration_deadline']));
        Schema::table('tours', function (Blueprint $t) {
            $t->dropConstrainedForeignId('agency_id');
            $t->dropColumn('cancellation_policy');
        });
        Schema::dropIfExists('agencies');
    }
};
