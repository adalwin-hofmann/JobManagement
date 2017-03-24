<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMarketingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('group_marketing', function($t) {
	        $t->engine = 'InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('group_id')->unsigned();
	        $t->string('subject', 128);
	        $t->string('name', 64);
	        $t->text('body');
	        $t->string('reply_name', 64);
	        $t->string('reply_email', 64);
	        $t->timestamps();
	         
	        $t->foreign('group_id')->references('id')->on('group')->onUpdate('cascade')->onDelete('cascade');
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
	    Schema::table('group_marketing', function ($t) {
	        $t->dropForeign('group_marketing_group_id_foreign');
	    });
	    
        Schema::drop('group_marketing');
	}

}
