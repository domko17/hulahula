<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string("title_before")->nullable(true);
            $table->string("first_name");
            $table->string("last_name");
            $table->string("title_after")->nullable(true);
            $table->char("gender")->nullable(true);
            $table->date("birthday")->nullable(true);
            $table->string("image")->nullable(true);
            $table->string('street')->nullable(true);
            $table->string('street_number')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('zip')->nullable(true);
            $table->string('country')->nullable(true);
            $table->string("phone")->nullable(true);
            $table->text('bio')->nullable(true);
            $table->boolean("set")->default(false);
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
        Schema::dropIfExists('user_profile');
    }
}
