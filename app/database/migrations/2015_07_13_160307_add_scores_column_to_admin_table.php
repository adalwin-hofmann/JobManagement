<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoresColumnToAdminTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::table('admin', function ($t) {
            $t->integer('apply_score');
            $t->integer('recruit_score');
            $t->integer('recruit_verify_score');
            $t->integer('recruit_success_score');
            $t->integer('invite_score');
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
