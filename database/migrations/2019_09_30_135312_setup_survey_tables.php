<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupSurveyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question');
            $table->integer('type');
            $table->boolean('students')->default(false);
            $table->boolean('teachers')->default(false);
            $table->timestamps();
        });

        Schema::create('survey_question_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('question_id');
            $table->bigInteger('user_id');
            $table->text('answer');
            $table->boolean('anonymous')->default(false);
            $table->timestamps();
        });

        Schema::create('survey_question_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('question_id');
            $table->bigInteger('language_id');
            $table->text('translation');
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
        Schema::dropIfExists('survey_questions');
    }
}
