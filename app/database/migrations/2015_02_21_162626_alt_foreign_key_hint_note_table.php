<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltForeignKeyHintNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('hint_note', function($t) {
			$t->dropForeign('hint_note_recommend_id_foreign');
			$t->dropForeign('hint_note_company_id_foreign');
				
			$t->foreign('recommend_id')->references('id')->on('job_recommend')->onUpdate('cascade')->onDelete('cascade');
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
		Schema::table('hint_note', function ($t) {
			$t->dropForeign('hint_note_recommend_id_foreign');
			$t->dropForeign('hint_note_company_id_foreign');
		});
		
		Schema::drop('hint_note');
	}

}
