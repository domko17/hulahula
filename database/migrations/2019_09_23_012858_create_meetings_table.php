<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->date('day');
            $table->text('comment')->nullable(true);
            $table->smallInteger('type');
            $table->bigInteger('language_id')->nullable(true);
            $table->timestamps();
        });

        Schema::create('user_meeting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('meeting_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('user_meeting');
    }
}
