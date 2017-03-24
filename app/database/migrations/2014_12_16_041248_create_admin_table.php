<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('admin', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->string('username', 64);
			$t->string('secure_key', 32);
			$t->string('salt', 8);
			$t->timestamps();
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
		Schema::drop('admin');
	}

}
