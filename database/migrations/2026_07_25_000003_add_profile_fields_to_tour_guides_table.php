<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToTourGuidesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tour_guides')) {
            Schema::table('tour_guides', function (Blueprint $table) {
                if (!Schema::hasColumn('tour_guides', 'tg_birth_date')) {
                    $table->date('tg_birth_date')->nullable()->after('tg_gender');
                }

                if (!Schema::hasColumn('tour_guides', 'tg_hometown')) {
                    $table->string('tg_hometown')->nullable()->after('tg_birth_date');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('tour_guides')) {
            Schema::table('tour_guides', function (Blueprint $table) {
                if (Schema::hasColumn('tour_guides', 'tg_hometown')) {
                    $table->dropColumn('tg_hometown');
                }

                if (Schema::hasColumn('tour_guides', 'tg_birth_date')) {
                    $table->dropColumn('tg_birth_date');
                }
            });
        }
    }
}
