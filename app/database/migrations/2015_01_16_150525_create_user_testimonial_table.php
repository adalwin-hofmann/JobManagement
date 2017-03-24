<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTestimonialTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_testimonial', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->string('name', 64);
			$t->string('organisation', 64);
			$t->text('notes');
		
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
		Schema::table('user_testimonial', function ($t) {
			$t->dropForeign('user_testimonial_user_id_foreign');
		});
		
		Schema::drop('user_testimonial');
	}

}
