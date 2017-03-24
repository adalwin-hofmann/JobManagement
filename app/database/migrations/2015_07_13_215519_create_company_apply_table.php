<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyApplyTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_apply', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('user_id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->string('name', 64);
            $t->text('description');
            $t->decimal('score', 2, 1);
            $t->string('attached_file', 64);
            $t->string('token', 32);

            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('company_apply', function ($t) {
            $t->dropForeign('company_apply_user_id_foreign');
            $t->dropForeign('company_apply_company_id_foreign');
        });

        Schema::drop('company_apply');
    }

}
