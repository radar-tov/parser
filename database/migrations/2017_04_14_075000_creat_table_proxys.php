<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableProxys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('port');
            $table->integer('type_id')->unsigned()->index();
            $table->foreign('type_id')->references('id')->on('types');
            $table->integer('anonymi_level_id')->unsigned()->index();
            $table->foreign('anonymi_level_id')->references('id')->on('anonymi_levels');
            $table->integer('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proxys');
    }
}
