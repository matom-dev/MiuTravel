<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('receiver_guard', 20);
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->string('type', 60)->default('general');
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('url')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['receiver_guard', 'receiver_id', 'read_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_notifications');
    }
}
