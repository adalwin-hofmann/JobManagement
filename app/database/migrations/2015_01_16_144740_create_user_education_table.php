<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEducationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_education', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->string('name', 128);
			$t->integer('start');
			$t->integer('end');
			$t->string('faculty', 128);
			$t->text('notes');
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
		Schema::table('user_education', function ($t) {
			$t->dropForeign('user_education_user_id_foreign');
		});
		
		Schema::drop('user_education');
	}

}
