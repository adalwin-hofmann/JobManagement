<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltStartAtAsNullableOnFaceInterviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    $prefix = DB::getTablePrefix();
	    DB::statement("ALTER TABLE ".$prefix."face_interview CHANGE `start_at` `start_at` VARCHAR(10) CHARSET utf8 COLLATE utf8_unicode_ci NULL");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	    $prefix = DB::getTablePrefix();
	    DB::statement("ALTER TABLE ".$prefix."face_interview CHANGE `start_at` `start_at` VARCHAR(10) CHARSET utf8 COLLATE utf8_unicode_ci NOT NULL");		
	}

}
