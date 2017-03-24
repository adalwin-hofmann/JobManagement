<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRecommendTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('job_recommend', function($t) {
			$t->engine = 'InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('user_id')->unsigned();
			$t->integer('job_id')->unsigned();
			$t->string('name', 64);
			$t->string('email', 128);
			$t->string('phone', 32);
			$t->string('currentJob', 256);
			$t->string('previousJobs', 256);
			$t->text('description');
			
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
		Schema::table('job_recommend', function ($t) {
			$t->dropForeign('job_recommend_user_id_foreign');
			$t->dropForeign('job_recommend_job_id_foreign');
		});
		
		Schema::drop('job_recommend');
	}

}
