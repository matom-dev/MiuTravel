<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivitiesAndGuidesToToursTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('tours')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 't_activities')) {
                $table->json('t_activities')->nullable()->after('t_content');
            }

            if (!Schema::hasColumn('tours', 't_guides')) {
                $table->json('t_guides')->nullable()->after('t_activities');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('tours')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 't_guides')) {
                $table->dropColumn('t_guides');
            }

            if (Schema::hasColumn('tours', 't_activities')) {
                $table->dropColumn('t_activities');
            }
        });
    }
}
