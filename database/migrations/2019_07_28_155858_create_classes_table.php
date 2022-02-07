<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("teacher_hour")->nullable(true);
            $table->bigInteger('collective_hour')->nullable(true);
            $table->date('class_date');
            $table->text('info')->nullable(true);
            $table->tinyInteger('canceled')->nullable(false)->default(0);
            $table->tinyInteger('teacher_paid')->nullable(false)->default(0);
            $table->text('cancel_reason')->nullable(true);
            $table->text('recording_url')->nullable(true);
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
        Schema::dropIfExists('classes');
    }
}
