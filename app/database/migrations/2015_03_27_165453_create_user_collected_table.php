<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCollectedTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_collected', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('city_id')->unsigned();
            $t->string('name', 32);
            $t->string('email', 32);
            $t->string('token', 10);
            $t->timestamps();

            $t->foreign('city_id')->references('id')->on('city')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('user_collected', function ($t) {
            $t->dropForeign('user_collected_city_id_foreign');
        });

        Schema::drop('user_collected');
    }

}
