<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFilterMetadataToHotelsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'h_accommodation_type')) {
                $table->string('h_accommodation_type', 30)->default('hotel')->after('h_phone');
            }
            if (!Schema::hasColumn('hotels', 'h_star_rating')) {
                $table->unsignedTinyInteger('h_star_rating')->nullable()->after('h_accommodation_type');
            }
            if (!Schema::hasColumn('hotels', 'h_amenities')) {
                $table->json('h_amenities')->nullable()->after('h_star_rating');
            }
            if (!Schema::hasColumn('hotels', 'h_suitable_for')) {
                $table->json('h_suitable_for')->nullable()->after('h_amenities');
            }
        });

        DB::table('hotels')->whereRaw('LOWER(h_name) LIKE ?', ['%resort%'])
            ->update(['h_accommodation_type' => 'resort']);
        DB::table('hotels')->whereRaw('LOWER(h_name) LIKE ?', ['%homestay%'])
            ->update(['h_accommodation_type' => 'homestay']);
        DB::table('hotels')->whereRaw('LOWER(h_name) LIKE ?', ['%villa%'])
            ->update(['h_accommodation_type' => 'villa']);
    }

    public function down()
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        $columns = array_values(array_filter([
            'h_accommodation_type',
            'h_star_rating',
            'h_amenities',
            'h_suitable_for',
        ], function ($column) {
            return Schema::hasColumn('hotels', $column);
        }));

        if (!$columns) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
}
