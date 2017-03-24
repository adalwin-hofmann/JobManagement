<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLabelTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_label', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->integer('user_id')->unsigned();
            $t->integer('label_id')->unsigned();
            $t->timestamps();

            $t->foreign('company_id')->references('id')->on('company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('label_id')->references('id')->on('label')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('user_label', function ($t) {
            $t->dropForeign('user_label_company_id_foreign');
            $t->dropForeign('user_label_user_id_foreign');
            $t->dropForeign('user_label_label_id_foreign');
        });

        Schema::drop('user_label');
    }

}
