<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegrityIndexesToBookingTables extends Migration
{
    public function up()
    {
        if (Schema::hasTable('book_tours')) {
            Schema::table('book_tours', function (Blueprint $table) {
                $table->index(['b_tour_id', 'b_status'], 'book_tours_tour_status_index');
                $table->index(['b_user_id', 'b_status'], 'book_tours_user_status_index');
                $table->index('created_at', 'book_tours_created_at_index');
            });
        }

        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->index(['t_status', 't_location_id'], 'tours_status_location_index');
            });
        }

        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->index(['cm_status', 'cm_tour_id'], 'comments_status_tour_index');
                $table->index(['cm_status', 'cm_article_id'], 'comments_status_article_index');
                $table->index(['cm_status', 'cm_hotel_id'], 'comments_status_hotel_index');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropIndex('comments_status_hotel_index');
                $table->dropIndex('comments_status_article_index');
                $table->dropIndex('comments_status_tour_index');
            });
        }

        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->dropIndex('tours_status_location_index');
            });
        }

        if (Schema::hasTable('book_tours')) {
            Schema::table('book_tours', function (Blueprint $table) {
                $table->dropIndex('book_tours_created_at_index');
                $table->dropIndex('book_tours_user_status_index');
                $table->dropIndex('book_tours_tour_status_index');
            });
        }
    }
}
