<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->string('name', 64);
			$t->string('email', 64);
			$t->integer('gender');
			$t->string('birthday', 32);
			$t->integer('year');
			$t->integer('category_id')->unsigned();
			$t->integer('city_id')->unsigned();
			$t->string('profile_image', 64);
			$t->string('cover_image', 64);
			$t->text('about');
			$t->string('professional_title', 64);
			$t->integer('level_id')->unsigned();
			$t->integer('communication_value');
			$t->text('communication_note');
			$t->integer('organisational_value');
			$t->text('organisational_note');
			$t->integer('job_related_value');
			$t->text('job_related_note');
			$t->integer('native_language_id')->unsigned();
			$t->text('hobbies');
			$t->decimal('renumeration_amount', 10, 2);
			$t->text('job_types');
			$t->string('phone', 32);
			$t->string('address', 256);
			$t->string('website', 512);
			$t->string('facebook', 512);
			$t->string('linkedin', 512);
			$t->string('twitter', 512);
			$t->string('google', 512);
			$t->decimal('lat', 12, 7);
			$t->decimal('lng', 12,7);
			$t->boolean('is_finished');
			$t->boolean('is_published');
			$t->boolean('is_active');
			$t->string('slug', 64);
			$t->string('secure_key', 32);
			$t->string('salt', 8);
			
			$t->timestamps();
			
			$t->foreign('category_id')->references('id')->on('category');
			$t->foreign('city_id')->references('id')->on('city');
			$t->foreign('level_id')->references('id')->on('level');
			$t->foreign('native_language_id')->references('id')->on('language');
			
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
		Schema::table('user', function ($t) {
			$t->dropForeign('user_category_id_foreign');
			$t->dropForeign('user_city_id_foreign');
			$t->dropForeign('user_level_id_foreign');
			$t->dropForeign('user_native_language_id_foreign');
		});
		
		Schema::drop('user');
	}
}
