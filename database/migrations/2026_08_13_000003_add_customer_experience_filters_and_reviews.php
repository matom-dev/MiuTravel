<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCustomerExperienceFiltersAndReviews extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tours') && !Schema::hasColumn('tours', 't_type')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->string('t_type', 30)->nullable()->after('t_location_id')->index();
            });
        }

        if (Schema::hasTable('car_rentals') && !Schema::hasColumn('car_rentals', 'cr_driver_option')) {
            Schema::table('car_rentals', function (Blueprint $table) {
                $table->string('cr_driver_option', 30)->nullable()->after('cr_vehicle_type')->index();
            });
        }

        if (Schema::hasTable('comments') && !Schema::hasColumn('comments', 'cm_rating')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedTinyInteger('cm_rating')->nullable()->after('cm_content');
            });
        }

        if (Schema::hasTable('comments')) {
            DB::table('comments')->where('cm_status', 1)->update(['cm_status' => 2]);
        }

        if (Schema::hasTable('car_rentals') && Schema::hasColumn('car_rentals', 'cr_vehicle_type')) {
            foreach ([
                'SUV' => 'suv',
                'Sedan' => 'sedan',
                'MPV' => 'mpv',
                'Van' => 'van',
                'Xe máy' => 'motorbike',
                'Nhiều loại xe' => 'multi',
                'Limousine' => 'limousine',
                'Xe khách' => 'bus',
            ] as $oldValue => $newValue) {
                DB::table('car_rentals')->where('cr_vehicle_type', $oldValue)->update(['cr_vehicle_type' => $newValue]);
            }
        }

        if (Schema::hasTable('car_rentals') && Schema::hasColumn('car_rentals', 'cr_driver_option')) {
            DB::table('car_rentals')->whereNull('cr_driver_option')->update(['cr_driver_option' => 'both']);
        }
    }

    public function down()
    {
        if (Schema::hasTable('comments')) {
            DB::table('comments')->where('cm_status', 2)->update(['cm_status' => 1]);
        }

        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'cm_rating')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('cm_rating');
            });
        }

        if (Schema::hasTable('car_rentals') && Schema::hasColumn('car_rentals', 'cr_driver_option')) {
            Schema::table('car_rentals', function (Blueprint $table) {
                $table->dropColumn('cr_driver_option');
            });
        }

        if (Schema::hasTable('tours') && Schema::hasColumn('tours', 't_type')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->dropColumn('t_type');
            });
        }
    }
}
