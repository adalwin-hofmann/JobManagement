<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAwardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_awards', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->string('name', 256);
			$t->string('prize', 256);
			$t->integer('year');
			$t->string('location', 256);
			
			$t->timestamps();
			
			$t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
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
		Schema::table('user_awards', function ($t) {
			$t->dropForeign('user_awards_user_id_foreign');
		});
		
		Schema::drop('user_awards');
	}

}
