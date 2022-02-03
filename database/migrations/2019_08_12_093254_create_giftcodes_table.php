<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giftcodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("code",25);
            $table->integer("stars_i");
            $table->integer("stars_c");
            $table->bigInteger("language_id")->nullable(true);
            $table->boolean("used")->default(false);
            $table->integer("used_by")->nullable(true)->comment("user_id");
            $table->text("comment")->nullable(true);
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
        Schema::dropIfExists('giftcodes');
    }
}
