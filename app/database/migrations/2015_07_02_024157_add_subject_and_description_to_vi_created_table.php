<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectAndDescriptionToViCreatedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::table('company_vi_created', function ($t) {
            $t->dropForeign('company_vi_created_template_id_foreign');
            $t->dropColumn('template_id');

            $t->string('subject', 256);
            $t->text('description');
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
