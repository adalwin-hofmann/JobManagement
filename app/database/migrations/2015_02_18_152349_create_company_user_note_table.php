<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyUserNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('company_user_note', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->integer('company_id')->unsigned();
			$t->text('notes');		
			$t->timestamps();
				
			$t->foreign('user_id')->references('id')->on('user');
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
		Schema::table('company_user_note', function ($t) {
			$t->dropForeign('company_user_note_user_id_foreign');
			$t->dropForeign('company_user_note_company_id_foreign');
		});
		
		Schema::drop('company_user_note');
	}

}
