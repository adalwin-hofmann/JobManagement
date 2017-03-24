<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('city', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('country_id')->unsigned();
			$t->string('name', 64);
			$t->timestamps();
			
			$t->foreign('country_id')->references('id')->on('country');
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
		Schema::table('city', function ($t) {
			$t->dropForeign('city_country_id_foreign');
		});
		
		Schema::drop('city');
	}

}
