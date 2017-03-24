<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewNoteTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('interview_note', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('cvr_id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->text('notes');
            $t->timestamps();

            $t->foreign('cvr_id')->references('id')->on('company_vi_response')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('interview_note', function ($t) {
            $t->dropForeign('interview_note_cvr_id_foreign');
            $t->dropForeign('interview_note_company_id_foreign');
        });

        Schema::drop('interview_note');
    }

}
