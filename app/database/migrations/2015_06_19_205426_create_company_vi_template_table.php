<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyViTemplateTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company_vi_template', function($t) {
            $t->engine ='InnoDB';
            $t->increments('id')->unsigned();
            $t->integer('company_id')->unsigned();
            $t->string('title', 256);
            $t->text('description');
            $t->timestamps();

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
        Schema::table('company_vi_template', function ($t) {
            $t->dropForeign('company_vi_template_company_id_foreign');
        });

        Schema::drop('company_vi_template');
    }

}
