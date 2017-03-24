<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBenefitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('benefits', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('job_id')->unsigned();
			$t->string('name', 64);
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
		Schema::table('benefits', function ($t) {
			$t->dropForeign('benefits_job_id_foreign');
		});
		
		Schema::drop('benefits');
	}

}
