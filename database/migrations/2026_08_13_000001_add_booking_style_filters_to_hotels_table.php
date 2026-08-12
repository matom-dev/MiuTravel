<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'h_room_facilities')) {
                $table->json('h_room_facilities')->nullable()->after('h_amenities');
            }
            if (!Schema::hasColumn('hotels', 'h_property_policies')) {
                $table->json('h_property_policies')->nullable()->after('h_room_facilities');
            }
            if (!Schema::hasColumn('hotels', 'h_meal_plans')) {
                $table->json('h_meal_plans')->nullable()->after('h_property_policies');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        $columns = array_values(array_filter([
            'h_room_facilities',
            'h_property_policies',
            'h_meal_plans',
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
};
