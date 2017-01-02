<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFsRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_material', function (Blueprint $table){
            $table->increments('id');
            $table->integer('fs_id')->unsigned();
            $table->string('name');
            $table->integer('quantity');
            $table->integer('price');
            $table->foreign('fs_id')->references('id')->on('feasibility_study')->onDelete('cascade');
        });

        Schema::create('work_force', function (Blueprint $table){
            $table->increments('id');
            $table->integer('fs_id')->unsigned();
            $table->string('name');
            $table->integer('time');
            $table->integer('cost');
            $table->foreign('fs_id')->references('id')->on('feasibility_study')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('raw_material');
        Schema::drop('work_force');
    }
}
