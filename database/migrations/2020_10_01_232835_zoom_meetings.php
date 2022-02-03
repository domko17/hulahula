<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ZoomMeetings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('teacher_hours_id');
            $table->foreign('teacher_hours_id')->references('id')->on('teacher_hours');
            $table->bigInteger('zoom_meeting_id')->nullable(false);
            $table->dateTime('start_datetime')->nullable(false);
            $table->integer('duration')->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('h323_password')->nullable(false);
            $table->string('pstn_password')->nullable(false);
            $table->string('encrypted_password')->nullable(false);
            $table->integer('type')->nullable(false);
            $table->string('join_url', 1500)->nullable(false);
            $table->string('start_url', 1500)->nullable(false);
            $table->integer('end_of_meeting')->nullable(true);
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
        Schema::dropIfExists('zoom_meetings');
    }
}
