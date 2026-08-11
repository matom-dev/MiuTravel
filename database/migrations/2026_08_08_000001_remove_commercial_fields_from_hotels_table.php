<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCommercialFieldsFromHotelsTable extends Migration
{
    private const LEGACY_COLUMNS = [
        'h_price',
        'h_price_contact',
        'h_sale',
    ];

    public function up()
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        foreach (self::LEGACY_COLUMNS as $column) {
            if (Schema::hasColumn('hotels', $column)) {
                Schema::table('hotels', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    public function down()
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'h_price')) {
                $table->integer('h_price')->nullable()->default(0)->after('h_anbum_image');
            }
            if (!Schema::hasColumn('hotels', 'h_price_contact')) {
                $table->tinyInteger('h_price_contact')->nullable()->default(0)->after('h_price');
            }
            if (!Schema::hasColumn('hotels', 'h_sale')) {
                $table->integer('h_sale')->default(0)->after('h_price_contact');
            }
        });
    }
}
