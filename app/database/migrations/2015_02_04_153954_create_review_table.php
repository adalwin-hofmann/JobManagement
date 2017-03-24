<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('review', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->integer('company_id')->unsigned();
			$t->decimal('score', 2, 1);
			$t->text('description');
			$t->boolean('is_approved');			
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
		Schema::table('review', function ($t) {
			$t->dropForeign('review_user_id_foreign');
			$t->dropForeign('review_company_id_foreign');
		});
		
		Schema::drop('review');
	}

}
