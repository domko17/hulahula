<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StarOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('star_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable(false);
            $table->string('variable_symbol')->nullable(true);
            $table->integer('stars_i')->nullable(false);
            $table->integer('stars_c')->nullable(false);
            $table->integer('discount_i')->nullable(false);
            $table->integer('discount_c')->nullable(false)->default(0);
            $table->double('price')->nullable(false);
            $table->bigInteger('package_id')->nullable(true);
            $table->tinyInteger('paid')->nullable(false)->default(0);
            $table->tinyInteger('canceled')->nullable(true)->default(0);
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
        Schema::dropIfExists('star_order');
    }
}
