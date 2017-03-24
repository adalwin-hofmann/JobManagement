<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectionTemplateToCompany extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('company', function ($t) {
            $t->string('apply_rejection_title', 256);
            $t->text('apply_rejection_content');
            $t->string('hint_rejection_title', 256);
            $t->text('hint_rejection_content');
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
