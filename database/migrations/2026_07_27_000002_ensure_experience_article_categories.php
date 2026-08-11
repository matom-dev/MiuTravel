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

        $now = now();
        $categories = [
            'kinh-nghiem-du-lich' => 'Kinh nghiệm du lịch',
            'dac-san' => 'Đặc sản địa phương',
        ];

        foreach ($categories as $slug => $name) {
            $category = DB::table('categories')->where('c_slug', $slug)->first();

            if ($category) {
                DB::table('categories')->where('id', $category->id)->update([
                    'c_name' => $name,
                    'c_status' => 1,
                    'c_type' => $category->c_type ?: 2,
                    'updated_at' => $now,
                ]);
                continue;
            }

            DB::table('categories')->insert([
                    'c_name' => $name,
                    'c_slug' => $slug,
                    'c_status' => 1,
                    'c_type' => 2,
                    'c_user_id' => null,
                    'c_parent_id' => null,
                    'updated_at' => $now,
                    'created_at' => $now,
            ]);
        }
    }

    public function down()
    {
        //
    }
};
