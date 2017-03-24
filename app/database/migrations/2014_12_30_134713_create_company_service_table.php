<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('company_service', function($t) {
			$t->engine ='InnoDB';
			$t->increments('id')->unsigned();
			$t->integer('company_id')->unsigned();
			$t->integer('service_id')->unsigned();
			$t->text('description');
			
			$t->timestamps();
				
			$t->foreign('company_id')->references('id')->on('company');
			$t->foreign('service_id')->references('id')->on('service');
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
		Schema::table('company_service', function ($t) {
			$t->dropForeign('company_service_company_id_foreign');
			$t->dropForeign('company_service_service_id_foreign');
		});
		
		Schema::drop('company_service');
	}

}
