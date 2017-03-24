<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyUserInviteTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_user_invite', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('user_id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->integer('job_id')->unsigned();
            $t->timestamps();

            $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('job_id')->references('id')->on('job')->onUpdate('cascade')->onUpdate('cascade');
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
        Schema::table('company_user_invite', function ($t) {
            $t->dropForeign('company_user_invite_company_id_foreign');
            $t->dropForeign('company_user_invite_user_id_foreign');
            $t->dropForeign('company_user_invite_job_id_foreign');
        });

        Schema::drop('company_user_invite');
    }

}
