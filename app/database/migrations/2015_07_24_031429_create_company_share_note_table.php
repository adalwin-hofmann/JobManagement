<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyShareNoteTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_share_note', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->integer('share_id')->unsigned();
            $t->text('note');
            $t->timestamps();

            $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('share_id')->references('id')->on('agency_share')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('company_share_note', function ($t) {
            $t->dropForeign('company_share_note_company_id_foreign');
            $t->dropForeign('company_share_note_share_id_foreign');
        });

        Schema::drop('company_share_note');
    }

}
