<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenderToTourGuidesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tour_guides') && !Schema::hasColumn('tour_guides', 'tg_gender')) {
            Schema::table('tour_guides', function (Blueprint $table) {
                $table->string('tg_gender', 20)->nullable()->after('tg_role');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('tour_guides') && Schema::hasColumn('tour_guides', 'tg_gender')) {
            Schema::table('tour_guides', function (Blueprint $table) {
                $table->dropColumn('tg_gender');
            });
        }
    }
}
