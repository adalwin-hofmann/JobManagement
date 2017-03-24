<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('apply_note', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('apply_id')->unsigned();
			$t->integer('company_id')->unsigned();
			$t->text('notes');		
			$t->timestamps();
				
			$t->foreign('apply_id')->references('id')->on('apply');
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
		Schema::table('apply_note', function ($t) {
			$t->dropForeign('apply_note_apply_id_foreign');
			$t->dropForeign('apply_note_company_id_foreign');
		});
		
		Schema::drop('apply_note');
	}

}
