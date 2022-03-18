<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectiveHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collective_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment("teacher_id");
            $table->bigInteger('sub_user_id')->comment("teacher_sub_id");
            $table->string("day", 255)->comment("1-5 | Mon-Fri");
            $table->time("class_start");
            $table->time('class_end');
            $table->bigInteger("language_id");
            $table->tinyInteger("class_difficulty")->comment("1-5 | A1-C1");
            $table->integer("class_limit");
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
        Schema::dropIfExists('collective_hours');
    }
}
