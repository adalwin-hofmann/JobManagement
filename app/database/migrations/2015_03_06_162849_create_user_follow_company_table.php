<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowCompanyTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_follow_company', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('user_id')->unsigned();
            $t->integer('follow_company_id')->unsigned();
            $t->timestamps();

            $t->foreign('follow_company_id')->references('id')->on('follow_company')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('user_follow_company', function ($t) {
            $t->dropForeign('user_follow_company_follow_company_id_foreign');
            $t->dropForeign('user_follow_company_user_id_foreign');
        });

        Schema::drop('user_follow_company');
    }


}
