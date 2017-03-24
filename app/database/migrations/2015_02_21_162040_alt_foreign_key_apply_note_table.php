<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltForeignKeyApplyNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('apply_note', function($t) {
			$t->dropForeign('apply_note_apply_id_foreign');
			$t->dropForeign('apply_note_company_id_foreign');
			
			$t->foreign('apply_id')->references('id')->on('apply')->onUpdate('cascade')->onDelete('cascade');
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
		Schema::table('apply_note', function ($t) {
			$t->dropForeign('apply_note_apply_id_foreign');
			$t->dropForeign('apply_note_company_id_foreign');
		});
		
		Schema::drop('apply_note');
	}

}
