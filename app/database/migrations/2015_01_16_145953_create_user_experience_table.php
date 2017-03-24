<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExperienceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_experience', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->string('name', 64);
			$t->string('position', 128);
			$t->integer('type_id')->unsigned();
			$t->text('notes');
			
			$t->timestamps();
			
			$t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
			$t->foreign('type_id')->references('id')->on('type');
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
		Schema::table('user_experience', function ($t) {
			$t->dropForeign('user_experience_user_id_foreign');
			$t->dropForeign('user_experience_type_id_foreign');
		});
		
		Schema::drop('user_experience');
	}

}
