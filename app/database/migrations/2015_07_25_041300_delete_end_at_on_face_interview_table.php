<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteEndAtOnFaceInterviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('face_interview', function ($t) {
	        $t->dropColumn('end_at');
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
	    Schema::table('face_interview', function($t) {
	        $t->string('end_at', 8)->after('start_at');
	    });		
	}

}
