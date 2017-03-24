<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('user', function ($t) {
			$t->dropcolumn('job_types');
			$t->boolean('is_freelance');
			$t->boolean('is_parttime');
			$t->boolean('is_fulltime');
			$t->boolean('is_internship');
			$t->boolean('is_volunteer');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
	}

}
