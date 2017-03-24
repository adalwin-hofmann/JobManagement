<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHintNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('hint_note', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('recommend_id')->unsigned();
			$t->integer('company_id')->unsigned();
			$t->text('notes');		
			$t->timestamps();
				
			$t->foreign('recommend_id')->references('id')->on('job_recommend');
			$t->foreign('company_id')->references('id')->on('company');
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
		Schema::table('hint_note', function ($t) {
			$t->dropForeign('hint_note_recommend_id_foreign');
			$t->dropForeign('hint_note_company_id_foreign');
		});
		
		Schema::drop('hint_note');
	}

}
