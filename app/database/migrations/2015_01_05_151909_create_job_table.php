<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('job', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->string('name', 64);
			$t->integer('level_id')->unsigned();
			$t->text('description');
			$t->integer('company_id')->unsigned();
			$t->integer('category_id')->unsigned();
			$t->integer('presence_id')->unsigned();
			$t->integer('year');
			$t->integer('city_id')->unsigned();
			$t->integer('native_language_id')->unsigned();
			$t->string('requirements', 512);
			$t->boolean('is_name');
			$t->boolean('is_phonenumber');
			$t->boolean('is_email');
			$t->boolean('is_previousjobs');
			$t->boolean('is_description');
			$t->decimal('bonus', 10, 2);
			$t->string('paid_after', 256);
			$t->text('bonus_description');
			$t->integer('type_id')->unsigned();
			$t->decimal('salary', 10, 2);
			$t->string('email', 64);
			$t->string('phone', 32);
			$t->decimal('lat', 11, 5);
			$t->decimal('long', 11, 5);
			$t->boolean('is_published');
			$t->boolean('is_finished');
			$t->boolean('is_active');
			$t->string('slug', 64);				
			
			$t->timestamps();
				
			$t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
			$t->foreign('level_id')->references('id')->on('level');
			$t->foreign('category_id')->references('id')->on('category');
			$t->foreign('presence_id')->references('id')->on('presence');
			$t->foreign('city_id')->references('id')->on('city');
			$t->foreign('native_language_id')->references('id')->on('language');
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
		Schema::table('job', function ($t) {
			$t->dropForeign('job_company_id_foreign');
			$t->dropForeign('job_level_id_foreign');
			$t->dropForeign('job_category_id_foreign');
			$t->dropForeign('job_presence_id_foreign');
			$t->dropForeign('job_city_id_foreign');
			$t->dropForeign('job_native_language_id_foreign');
			$t->dropForeign('job_type_id_foreign');			
		});
		
		Schema::drop('job');
	}
}
