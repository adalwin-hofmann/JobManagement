<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_questions', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('questionnaires_id')->unsigned();
            $t->integer('questions_id')->unsigned();
            $t->timestamps();

            $t->foreign('questionnaires_id')->references('id')->on('questionnaires')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('questions_id')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('questionnaire_questions', function ($t) {
            $t->dropForeign('questionnaire_questions_questionnaires_id_foreign');
            $t->dropForeign('questionnaire_questions_questions_id_foreign');
        });

        Schema::drop('questionnaire_questions');
    }

}
