<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('message', function($t) {
    	    $t->engine ='InnoDB';
    	    $t->increments('id')->unsigned();
    	    $t->integer('job_id')->unsigned()->nullable();
    	    $t->integer('company_id')->unsigned();
    	    $t->integer('user_id')->unsigned();
    	    $t->text('description');
    	    $t->boolean('is_company_sent')->default(0);
    	    $t->boolean('is_read')->default(0);
    	    $t->timestamps();
    	    $t->foreign('job_id')->references('id')->on('job');
    	    $t->foreign('company_id')->references('id')->on('company');
    	    $t->foreign('user_id')->references('id')->on('user');
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
	    Schema::table('message', function ($t) {
	        $t->dropForeign('message_job_id_foreign');
	        $t->dropForeign('message_company_id_foreign');
	        $t->dropForeign('message_user_id_foreign');
	    });
	    
        Schema::drop('message');		
	}

}
