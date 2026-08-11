<?php

namespace Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreatesTestSchema
{
    protected function createLegacyTestSchema(): void
    {
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('l_name');
                $table->string('l_slug')->nullable();
                $table->string('l_image')->nullable();
                $table->text('l_description')->nullable();
                $table->longText('l_content')->nullable();
                $table->unsignedTinyInteger('l_status')->default(1);
                $table->unsignedBigInteger('l_user_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('c_name');
                $table->unsignedBigInteger('c_parent_id')->nullable();
                $table->string('c_slug')->nullable();
                $table->string('c_avatar')->nullable();
                $table->string('c_banner')->nullable();
                $table->text('c_description')->nullable();
                $table->unsignedTinyInteger('c_hot')->default(0);
                $table->unsignedTinyInteger('c_status')->default(1);
                $table->unsignedTinyInteger('c_type')->default(1);
                $table->unsignedBigInteger('c_user_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tours')) {
            Schema::create('tours', function (Blueprint $table) {
                $table->id();
                $table->string('t_title');
                $table->string('t_journeys')->nullable();
                $table->string('t_schedule')->nullable();
                $table->string('t_move_method')->nullable();
                $table->string('t_starting_gate')->nullable();
                $table->dateTime('t_start_date')->nullable();
                $table->dateTime('t_end_date')->nullable();
                $table->unsignedSmallInteger('t_duration_days')->nullable();
                $table->unsignedSmallInteger('t_duration_nights')->nullable();
                $table->unsignedInteger('t_number_guests')->default(0);
                $table->unsignedInteger('t_price_adults')->default(0);
                $table->unsignedInteger('t_price_children')->default(0);
                $table->unsignedTinyInteger('t_sale')->default(0);
                $table->unsignedInteger('t_view')->default(0);
                $table->longText('t_description')->nullable();
                $table->longText('t_content')->nullable();
                $table->json('t_activities')->nullable();
                $table->json('t_guides')->nullable();
                $table->json('t_anbum_image')->nullable();
                $table->string('t_image')->nullable();
                $table->unsignedBigInteger('t_location_id')->nullable();
                $table->unsignedBigInteger('t_user_id')->nullable();
                $table->unsignedInteger('t_number_registered')->default(0);
                $table->unsignedInteger('t_follow')->default(0);
                $table->unsignedTinyInteger('t_status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('a_title');
                $table->string('a_slug')->nullable();
                $table->unsignedTinyInteger('a_hot')->default(0);
                $table->unsignedTinyInteger('a_active')->default(1);
                $table->unsignedInteger('a_view')->default(0);
                $table->text('a_description')->nullable();
                $table->string('a_avatar')->nullable();
                $table->json('a_album_images')->nullable();
                $table->longText('a_content')->nullable();
                $table->unsignedBigInteger('a_category_id')->nullable();
                $table->unsignedBigInteger('a_user_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hotels')) {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id();
                $table->string('h_name');
                $table->string('h_image')->nullable();
                $table->json('h_anbum_image')->nullable();
                $table->string('h_address')->nullable();
                $table->string('h_phone')->nullable();
                $table->string('h_accommodation_type', 30)->default('hotel');
                $table->unsignedTinyInteger('h_star_rating')->nullable();
                $table->json('h_amenities')->nullable();
                $table->json('h_suitable_for')->nullable();
                $table->longText('h_description')->nullable();
                $table->longText('h_content')->nullable();
                $table->unsignedTinyInteger('h_status')->default(1);
                $table->unsignedBigInteger('h_location_id')->nullable();
                $table->unsignedBigInteger('h_user_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cm_reply_id')->nullable();
                $table->unsignedBigInteger('cm_user_id')->nullable();
                $table->unsignedBigInteger('cm_article_id')->nullable();
                $table->unsignedBigInteger('cm_hotel_id')->nullable();
                $table->unsignedBigInteger('cm_tour_id')->nullable();
                $table->text('cm_content')->nullable();
                $table->json('cm_images')->nullable();
                $table->unsignedTinyInteger('cm_status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('book_tours')) {
            Schema::create('book_tours', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('b_tour_id');
                $table->unsignedBigInteger('b_tour_schedule_id')->nullable();
                $table->unsignedBigInteger('b_user_id');
                $table->string('b_name');
                $table->string('b_email');
                $table->string('b_phone');
                $table->string('b_address')->nullable();
                $table->dateTime('b_start_date')->nullable();
                $table->dateTime('b_end_date')->nullable();
                $table->text('b_note')->nullable();
                $table->unsignedInteger('b_number_adults')->default(0);
                $table->unsignedInteger('b_number_children')->default(0);
                $table->unsignedInteger('b_number_child6')->default(0);
                $table->unsignedInteger('b_number_child2')->default(0);
                $table->unsignedInteger('b_price_adults')->default(0);
                $table->unsignedInteger('b_price_children')->default(0);
                $table->unsignedInteger('b_price_child6')->default(0);
                $table->unsignedInteger('b_price_child2')->default(0);
                $table->unsignedTinyInteger('b_status')->default(1);
                $table->timestamps();
            });
        }
    }
}
