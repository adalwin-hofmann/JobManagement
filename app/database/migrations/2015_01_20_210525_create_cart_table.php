<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('cart', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->integer('job_id')->unsigned();
			
			$t->timestamps();
			
			$t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
			$t->foreign('job_id')->references('id')->on('job')->onUpdate('cascade')->onDelete('cascade');
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
		Schema::table('cart', function ($t) {
			$t->dropForeign('cart_user_id_foreign');
			$t->dropForeign('cart_job_id_foreign');
		});
		
		Schema::drop('cart');
	}

}
