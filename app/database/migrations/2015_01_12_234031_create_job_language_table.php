<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('job_language', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('job_id')->unsigned();
			$t->integer('language_id')->unsigned();
			$t->string('name', 256);
			$t->integer('understanding');
			$t->integer('speaking');
			$t->integer('writing');
			$t->timestamps();
			
			$t->foreign('job_id')->references('id')->on('job')->onUpdate('cascade')->onDelete('cascade');
			$t->foreign('language_id')->references('id')->on('language');
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
		Schema::table('job_language', function ($t) {
			$t->dropForeign('job_language_job_id_foreign');
			$t->dropForeign('job_language_language_id_foreign');
		});
		
		Schema::drop('job_language');
	}

}
