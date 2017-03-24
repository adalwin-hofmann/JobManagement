<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdOnFaceInterviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('face_interview', function($t) {
	        $t->integer('user_id')->unsigned()->after('company_id');
	        $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
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
	    Schema::table('face_interview', function ($t) {
	        $t->dropForeign('face_interview_user_id_foreign');
	        $t->dropColumn('user_id');
	    });
	}

}
