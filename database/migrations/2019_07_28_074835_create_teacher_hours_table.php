<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment("teacher_id");
            $table->tinyInteger("day")->comment("1-5 | Mon-Fri");
            $table->time("class_start");
            $table->time('class_end');
            $table->bigInteger("language_id");
            $table->tinyInteger("class_difficulty")->comment("1-5 | A1-C1");
            $table->boolean('active');
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
        Schema::dropIfExists('teacher_hours');
    }
}
