<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('comments')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'cm_images')) {
                $table->json('cm_images')->nullable()->after('cm_content');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('comments')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'cm_images')) {
                $table->dropColumn('cm_images');
            }
        });
    }
};
