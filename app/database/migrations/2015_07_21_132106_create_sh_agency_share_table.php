<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShAgencyShareTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('agency_share', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('agency_id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->integer('job_id')->unsigned()->nullable();
            $t->integer('user_id')->unsigned()->nullable();
            $t->integer('interview_id')->unsigned()->nullable();
            $t->text('note');
            $t->timestamps();

            $t->foreign('agency_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('job_id')->references('id')->on('job')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('interview_id')->references('id')->on('company_vi_created')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('agency_share', function ($t) {
            $t->dropForeign('agency_share_agency_id_foreign');
            $t->dropForeign('agency_share_company_id_foreign');
            $t->dropForeign('agency_share_job_id_foreign');
            $t->dropForeign('agency_share_user_id_foreign');
            $t->dropForeign('agency_share_interview_id_foreign');
        });

        Schema::drop('agency_share');
    }

}
