<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRentalsTable extends Migration
{
    public function up()
    {
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->string('cr_name');
            $table->string('cr_image')->nullable();
            $table->json('cr_album_images')->nullable();
            $table->unsignedBigInteger('cr_location_id')->nullable();
            $table->unsignedBigInteger('cr_user_id')->nullable();
            $table->string('cr_vehicle_type')->nullable();
            $table->unsignedSmallInteger('cr_number_seats')->nullable();
            $table->string('cr_transmission', 50)->nullable();
            $table->string('cr_fuel', 50)->nullable();
            $table->decimal('cr_price', 15, 2)->default(0);
            $table->string('cr_phone', 30)->nullable();
            $table->string('cr_address')->nullable();
            $table->text('cr_description')->nullable();
            $table->longText('cr_content')->nullable();
            $table->tinyInteger('cr_status')->default(1)->comment('1: Xuat ban, 2: Ban nhap');
            $table->timestamps();

            $table->index(['cr_status', 'cr_location_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_rentals');
    }
}
