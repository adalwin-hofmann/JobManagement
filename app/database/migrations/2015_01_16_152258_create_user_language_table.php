<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_language', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->integer('language_id')->unsigned();
			$t->integer('understanding');
			$t->integer('speaking');
			$t->integer('writing');
		
			$t->timestamps();
			
			$t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
			$t->foreign('language_id')->references('id')->on('language');
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
		Schema::table('user_language', function ($t) {
			$t->dropForeign('user_language_user_id_foreign');
			$t->dropForeign('user_language_language_id_foreign');
		});
		
		Schema::drop('user_language');
	}

}
