<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgencyClientTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('agency_client', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('agency_id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->text('note');
            $t->timestamps();

            $t->foreign('agency_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('agency_client', function ($t) {
            $t->dropForeign('agency_client_agency_id_foreign');
            $t->dropForeign('agency_client_company_id_foreign');
        });

        Schema::drop('agency_client');
    }

}
