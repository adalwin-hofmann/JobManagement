<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('group_company', function($t) {
	        $t->engine = 'InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('group_id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->timestamps();
	    
	        $t->foreign('group_id')->references('id')->on('group')->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
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
	    Schema::table('group_company', function ($t) {
	        $t->dropForeign('group_company_group_id_foreign');
	        $t->dropForeign('group_company_company_id_foreign');
	    });
	    
        Schema::drop('group_company');
	}

}
