<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyViCreatedTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_vi_created', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->integer('user_id')->unsigned();
            $t->integer('job_id')->unsigned()->nullable();
            $t->integer('template_id')->unsigned();
            $t->integer('questionnaire_id')->unsigned();
            $t->string('expire_at', 32);
            $t->string('token', 32);
            $t->timestamps();

            $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('job_id')->references('id')->on('job')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('template_id')->references('id')->on('company_vi_template')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('questionnaire_id')->references('id')->on('questionnaires')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('company_vi_created', function ($t) {
            $t->dropForeign('company_vi_created_company_id_foreign');
            $t->dropForeign('company_vi_created_user_id_foreign');
            $t->dropForeign('company_vi_created_job_id_foreign');
            $t->dropForeign('company_vi_created_template_id_foreign');
            $t->dropForeign('company_vi_created_questionnaire_id_foreign');
        });

        Schema::drop('company_vi_created');
    }

}
