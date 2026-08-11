<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlexibleDurationToToursAndBookings extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                if (!Schema::hasColumn('tours', 't_duration_days')) {
                    $table->unsignedSmallInteger('t_duration_days')->nullable()->after('t_schedule');
                }

                if (!Schema::hasColumn('tours', 't_duration_nights')) {
                    $table->unsignedSmallInteger('t_duration_nights')->nullable()->after('t_duration_days');
                }
            });
        }

        if (Schema::hasTable('book_tours')) {
            Schema::table('book_tours', function (Blueprint $table) {
                if (!Schema::hasColumn('book_tours', 'b_end_date')) {
                    $table->dateTime('b_end_date')->nullable()->after('b_start_date');
                    $table->index('b_end_date', 'book_tours_end_date_index');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('book_tours') && Schema::hasColumn('book_tours', 'b_end_date')) {
            Schema::table('book_tours', function (Blueprint $table) {
                $table->dropIndex('book_tours_end_date_index');
                $table->dropColumn('b_end_date');
            });
        }

        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                if (Schema::hasColumn('tours', 't_duration_nights')) {
                    $table->dropColumn('t_duration_nights');
                }

                if (Schema::hasColumn('tours', 't_duration_days')) {
                    $table->dropColumn('t_duration_days');
                }
            });
        }
    }
}
