<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        DB::table('categories')
            ->where('c_slug', 'dac-san')
            ->update([
                'c_name' => 'Đặc sản địa phương',
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        DB::table('categories')
            ->where('c_slug', 'dac-san')
            ->update([
                'c_name' => 'Đặc sản',
                'updated_at' => now(),
            ]);
    }
};
