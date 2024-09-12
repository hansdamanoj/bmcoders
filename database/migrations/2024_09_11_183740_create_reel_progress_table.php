<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReelProgressTable extends Migration
{
    public function up()
    {
        Schema::create('reel_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reel_id');
            $table->integer('last_second')->default(0);
            $table->timestamps();

            // Unique constraint to avoid duplicate entries for the same user and reel
            $table->unique(['user_id', 'reel_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reel_progress');
    }
}
