<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSkillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('job_skill', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('job_id')->unsigned();
			$t->string('name', 256);
			$t->integer('value');
			$t->timestamps();
			
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
		Schema::table('job_skill', function ($t) {
			$t->dropForeign('job_skill_job_id_foreign');
		});
		
		Schema::drop('job_skill');
	}

}
