<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('articles') || Schema::hasColumn('articles', 'a_album_images')) {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->json('a_album_images')->nullable()->after('a_avatar');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('articles') || !Schema::hasColumn('articles', 'a_album_images')) {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('a_album_images');
        });
    }
};
