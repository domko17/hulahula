<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->nullable(false);
            $table->integer('state')->nullable(false)->default(0)->comment('0-unused, 1-active, 2-ending_soon, 3-used, 4-renewal');
            $table->bigInteger('renewal_package_id')->nullable(true);
            $table->bigInteger('last_class_id')->nullable(true);
            $table->bigInteger('user_id')->nullable(true);
            $table->bigInteger('classes_left')->nullable(true);
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
        Schema::dropIfExists('user_packages');
    }
}
