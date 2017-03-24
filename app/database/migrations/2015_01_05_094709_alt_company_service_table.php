<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltCompanyServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('company_service', function ($t) {
			$t->dropForeign('company_service_company_id_foreign');
			$t->dropForeign('company_service_service_id_foreign');
			$t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
			$t->foreign('service_id')->references('id')->on('service')->onUpdate('cascade')->onDelete('cascade');
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
	}

}
