<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAdminToCompany extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('company', function ($t) {
			$t->boolean('is_admin');
			$t->integer('parent_id')->unsigned()->nullable();
			
			$t->foreign('parent_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
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
		Schema::table('company', function ($t) {
			$t->dropForeign('company_parent_id_foreign');
		});
		
	}

}
