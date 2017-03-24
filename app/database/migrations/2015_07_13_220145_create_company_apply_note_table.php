<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyApplyNoteTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_apply_note', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('user_id')->unsigned();
            $t->integer('apply_id')->unsigned();
            $t->text('notes');

            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('apply_id')->references('id')->on('company_apply')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('company_apply_note', function ($t) {
            $t->dropForeign('company_apply_note_user_id_foreign');
            $t->dropForeign('company_apply_note_apply_id_foreign');
        });

        Schema::drop('company_apply_note');
    }

}
