<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('recipients')->nullable(false);
            $table->string('subject')->nullable(false);
            $table->tinyInteger('status')->nullable(false);
            $table->string('module')->nullable(false);
            $table->text('data')->nullable(true);
            $table->dateTime('send_time')->useCurrent();
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
        Schema::dropIfExists('email_messages');
    }
}
