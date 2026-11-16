<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platform_fee_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->unsignedInteger('basis_points')->default(1500);
            $table->timestamps();
        });
        DB::table('platform_fee_settings')->insert(['id'=>1, 'basis_points'=>1500, 'created_at'=>now(), 'updated_at'=>now()]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_fee_settings');
    }
};
