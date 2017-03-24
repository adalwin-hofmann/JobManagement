<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyViResponseTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_vi_response', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('cvc_id')->unsigned();
            $t->integer('question_id')->unsigned();
            $t->string('file_name', 32);
            $t->timestamps();

            $t->foreign('cvc_id')->references('id')->on('company_vi_created')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('company_vi_response', function ($t) {
            $t->dropForeign('company_vi_response_cvc_id_foreign');
            $t->dropForeign('company_vi_response_question_id_foreign');
        });

        Schema::drop('company_vi_response');
    }

}
