<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Run before the feature migrations that extend these legacy tables.
        // Existing installations retain their schema and data.
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('l_name', 255)->nullable();
                $table->string('l_slug', 255)->nullable();
                $table->string('l_image', 255)->nullable();
                $table->string('l_description', 255)->nullable();
                $table->text('l_content')->nullable();
                $table->tinyInteger('l_status')->nullable()->default('0');
                $table->unsignedBigInteger('l_user_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['l_user_id'], 'locations_l_user_id_index');
                $table->foreign(['l_user_id'], 'locations_l_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('c_name', 255);
                $table->integer('c_parent_id')->nullable()->default('0');
                $table->string('c_slug', 255);
                $table->string('c_avatar', 255)->nullable();
                $table->string('c_banner', 255)->nullable();
                $table->string('c_description', 255)->nullable();
                $table->tinyInteger('c_hot')->default('0');
                $table->tinyInteger('c_status')->default('1');
                $table->tinyInteger('c_type')->nullable();
                $table->unsignedBigInteger('c_user_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['c_parent_id'], 'categories_c_parent_id_index');
                $table->unique(['c_slug'], 'categories_c_slug_unique');
                $table->index(['c_user_id'], 'categories_c_user_id_index');
                $table->foreign(['c_user_id'], 'categories_c_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tours')) {
            Schema::create('tours', function (Blueprint $table) {
                $table->id();
                $table->string('t_title', 255)->nullable();
                $table->string('t_journeys', 255)->nullable();
                $table->string('t_schedule', 255)->nullable();
                $table->string('t_move_method', 255)->nullable();
                $table->string('t_starting_gate', 255)->nullable();
                $table->date('t_start_date')->nullable();
                $table->date('t_end_date')->nullable();
                $table->integer('t_number_guests')->default('0');
                $table->integer('t_price_adults')->default('0');
                $table->integer('t_price_children')->default('0');
                $table->integer('t_sale')->default('0');
                $table->integer('t_view')->default('0');
                $table->text('t_description')->nullable();
                $table->text('t_content')->nullable();
                $table->text('t_anbum_image')->nullable();
                $table->string('t_image', 255)->nullable();
                $table->unsignedBigInteger('t_location_id')->nullable();
                $table->unsignedBigInteger('t_user_id')->nullable();
                $table->integer('t_number_registered')->nullable()->default('0');
                $table->integer('t_follow')->nullable()->default('0');
                $table->tinyInteger('t_status')->default('0');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['t_location_id'], 'tours_t_location_id_index');
                $table->index(['t_user_id'], 'tours_t_user_id_index');
                $table->foreign(['t_location_id'], 'tours_t_location_id_foreign')->references(['id'])->on('locations')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['t_user_id'], 'tours_t_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('a_title', 255);
                $table->string('a_slug', 255);
                $table->tinyInteger('a_show_home')->default('0');
                $table->tinyInteger('a_active')->default('1');
                $table->integer('a_view')->default('0');
                $table->text('a_description')->nullable();
                $table->string('a_avatar', 255)->nullable();
                $table->text('a_content')->nullable();
                $table->unsignedBigInteger('a_category_id')->nullable();
                $table->unsignedBigInteger('a_user_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['a_active'], 'articles_a_active_index');
                $table->index(['a_category_id'], 'articles_a_category_id_index');
                $table->index(['a_show_home'], 'articles_a_show_home_index');
                $table->index(['a_slug'], 'articles_a_slug_index');
                $table->index(['a_user_id'], 'articles_a_user_id_index');
                $table->foreign(['a_category_id'], 'articles_a_category_id_foreign')->references(['id'])->on('categories')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['a_user_id'], 'articles_a_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('hotels')) {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id();
                $table->string('h_name', 255)->nullable();
                $table->string('h_image', 255)->nullable();
                $table->string('h_address', 255)->nullable();
                $table->string('h_phone', 255)->nullable();
                $table->text('h_anbum_image')->nullable();
                $table->text('h_description')->nullable();
                $table->text('h_content')->nullable();
                $table->tinyInteger('h_status')->default('0');
                $table->unsignedBigInteger('h_location_id')->nullable();
                $table->unsignedBigInteger('h_user_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['h_location_id'], 'hotels_h_location_id_index');
                $table->index(['h_user_id'], 'hotels_h_user_id_index');
                $table->foreign(['h_location_id'], 'hotels_h_location_id_foreign')->references(['id'])->on('locations')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['h_user_id'], 'hotels_h_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('cm_reply_id')->nullable();
                $table->unsignedBigInteger('cm_user_id')->nullable();
                $table->unsignedBigInteger('cm_article_id')->nullable();
                $table->unsignedBigInteger('cm_hotel_id')->nullable();
                $table->unsignedBigInteger('cm_tour_id')->nullable();
                $table->text('cm_content')->nullable();
                $table->tinyInteger('cm_status')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['cm_article_id'], 'comments_cm_article_id_index');
                $table->index(['cm_hotel_id'], 'comments_cm_hotel_id_index');
                $table->index(['cm_reply_id'], 'comments_cm_reply_id_index');
                $table->index(['cm_tour_id'], 'comments_cm_tour_id_index');
                $table->index(['cm_user_id'], 'comments_cm_user_id_index');
                $table->foreign(['cm_article_id'], 'comments_cm_article_id_foreign')->references(['id'])->on('articles')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['cm_hotel_id'], 'comments_cm_hotel_id_foreign')->references(['id'])->on('hotels')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['cm_tour_id'], 'comments_cm_tour_id_foreign')->references(['id'])->on('tours')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['cm_user_id'], 'comments_cm_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('book_tours')) {
            Schema::create('book_tours', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('b_tour_id')->nullable();
                $table->unsignedBigInteger('b_user_id')->nullable();
                $table->string('b_name', 255)->nullable();
                $table->string('b_email', 100)->nullable();
                $table->string('b_phone', 100)->nullable();
                $table->string('b_address', 100)->nullable();
                $table->date('b_start_date')->nullable();
                $table->text('b_note')->nullable();
                $table->integer('b_number_adults')->nullable()->default('0');
                $table->integer('b_number_children')->nullable()->default('0');
                $table->integer('b_number_child6');
                $table->integer('b_price_child2');
                $table->integer('b_number_child2');
                $table->integer('b_price_child6');
                $table->integer('b_price_adults')->default('0');
                $table->integer('b_price_children')->default('0');
                $table->tinyInteger('b_status')->nullable()->default('0');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['b_tour_id'], 'book_tours_b_tour_id_index');
                $table->index(['b_user_id'], 'book_tours_b_user_id_index');
                $table->foreign(['b_tour_id'], 'book_tours_b_tour_id_foreign')->references(['id'])->on('tours')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['b_user_id'], 'book_tours_b_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('subject', 255)->nullable();
                $table->text('message')->nullable();
                $table->unsignedBigInteger('c_user_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->index(['c_user_id'], 'contacts_c_user_id_index');
                $table->foreign(['c_user_id'], 'contacts_c_user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

    }

    public function down(): void
    {
        // Legacy tables may contain data imported before this migration existed.
        // Keep them on rollback, just like the legacy ACL baseline migration.
    }
};
