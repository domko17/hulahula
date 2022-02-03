<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 50)->nullable(true);
            $table->string('description', 200)->nullable(true);
            $table->smallInteger('type')->default(1);
            $table->smallInteger('bckg_colour')->default(1);
            $table->string('image')->nullable(true);
            $table->string('url')->nullable(true);
            $table->text('ext_link')->nullable(true);
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        Schema::create('banner_post_visibility', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('banner_id');
            $table->tinyInteger('type')->default(1);
            $table->text('language')->nullable(true); ///json
            $table->boolean('students')->default(false);
            $table->boolean('guests')->default(false);
            $table->boolean('teachers')->default(false);
            $table->text('user_id')->nullable(true); ///json
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
        Schema::dropIfExists('banner_posts');
        Schema::dropIfExists('banner_post_visibility');
    }
}
