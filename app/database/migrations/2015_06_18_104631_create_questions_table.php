<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questions', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->text('question');
            $t->integer('company_id')->unsigned();
            $t->boolean('by_admin');
            $t->timestamps();

            $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('questions', function ($t) {
            $t->dropForeign('questions_company_id_foreign');
        });

        Schema::drop('questions');
    }

}
