<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTourSchedulesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('tour_schedules')) {
            Schema::create('tour_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ts_tour_id');
                $table->dateTime('ts_start_date');
                $table->dateTime('ts_end_date');
                $table->unsignedInteger('ts_number_guests')->default(0);
                $table->unsignedInteger('ts_number_registered')->default(0);
                $table->unsignedInteger('ts_follow')->default(0);
                $table->unsignedTinyInteger('ts_status')->default(1);
                $table->timestamps();

                $table->index(['ts_tour_id', 'ts_status']);
                $table->index('ts_start_date');
            });
        }

        if (Schema::hasTable('book_tours') && !Schema::hasColumn('book_tours', 'b_tour_schedule_id')) {
            Schema::table('book_tours', function (Blueprint $table) {
                $table->unsignedBigInteger('b_tour_schedule_id')->nullable()->after('b_tour_id');
                $table->index('b_tour_schedule_id');
            });
        }

        if (Schema::hasTable('tours') && Schema::hasTable('tour_schedules') && DB::table('tour_schedules')->count() === 0) {
            DB::table('tours')->orderBy('id')->get()->each(function ($tour) {
                if (empty($tour->t_start_date) || empty($tour->t_end_date)) {
                    return;
                }

                $scheduleId = DB::table('tour_schedules')->insertGetId([
                    'ts_tour_id' => $tour->id,
                    'ts_start_date' => date('Y-m-d H:i:s', strtotime($tour->t_start_date)),
                    'ts_end_date' => date('Y-m-d H:i:s', strtotime($tour->t_end_date)),
                    'ts_number_guests' => max(0, (int) $tour->t_number_guests),
                    'ts_number_registered' => max(0, (int) $tour->t_number_registered),
                    'ts_follow' => max(0, (int) $tour->t_follow),
                    'ts_status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if (Schema::hasTable('book_tours') && Schema::hasColumn('book_tours', 'b_tour_schedule_id')) {
                    DB::table('book_tours')
                        ->where('b_tour_id', $tour->id)
                        ->whereNull('b_tour_schedule_id')
                        ->update(['b_tour_schedule_id' => $scheduleId]);
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('book_tours') && Schema::hasColumn('book_tours', 'b_tour_schedule_id')) {
            Schema::table('book_tours', function (Blueprint $table) {
                $table->dropIndex(['b_tour_schedule_id']);
                $table->dropColumn('b_tour_schedule_id');
            });
        }

        Schema::dropIfExists('tour_schedules');
    }
}
