<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('company', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->string('name', 64);
			$t->string('tag', 512);
			$t->integer('year');
			$t->integer('teamsize_id')->unsigned();
			$t->integer('category_id')->unsigned();
			$t->integer('city_id')->unsigned();
			$t->string('logo', 128);
			$t->text('description');
			$t->string('expertise', 512);
			$t->string('address', 128);
			$t->string('phone', 32);
			$t->string('email', 64);
			$t->string('website', 512);
			$t->string('facebook', 512);
			$t->string('linkedin', 512);
			$t->string('twitter', 512);
			$t->string('google', 512);
			$t->decimal('lat', 21, 14);
			$t->decimal('long', 21, 14);
			$t->boolean('is_published');
			$t->boolean('is_finished');
			$t->boolean('is_active');
			$t->string('slug', 64);
			$t->string('salt', 8);
			$t->string('secure_key', 32);
			
			$t->timestamps();
				
			$t->foreign('teamsize_id')->references('id')->on('teamsize');
			$t->foreign('category_id')->references('id')->on('category');
			$t->foreign('city_id')->references('id')->on('city');
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
		Schema::table('company', function ($t) {
			$t->dropForeign('company_teamsize_id_foreign');
			$t->dropForeign('company_category_id_foreign');
			$t->dropForeign('company_city_id_foreign');
		});
		
		Schema::drop('company');
	}

}
