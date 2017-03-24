<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaires', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->text('title');
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
        Schema::table('questionnaires', function ($t) {
            $t->dropForeign('questionnaires_company_id_foreign');
        });

        Schema::drop('questionnaires');
    }

}
