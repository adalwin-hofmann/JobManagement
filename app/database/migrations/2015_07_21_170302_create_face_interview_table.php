<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceInterviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('face_interview', function($t) {
	        $t->engine = 'InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->string('interview_date', 10);
	        $t->string('start_at', 8);
	        $t->string('end_at', 8);
	        $t->string('title', 64);
	        $t->string('name', 64);
	        $t->string('email', 64);
	        $t->text('description');
	        $t->timestamps();
	        $t->foreign('company_id')->references('id')
	                ->on('company')->onUpdate('cascade')->onDelete('cascade');
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
	        $t->dropForeign('face_interview_company_id_foreign');
	    });

        Schema::drop('face_interview');
    }
    
}
