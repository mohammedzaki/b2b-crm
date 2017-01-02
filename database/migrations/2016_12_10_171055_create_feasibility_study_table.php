<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeasibilityStudyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feasibility_study', function (Blueprint $table) {
            $table->increments('id');
            $table->string('process_name')->unique()->nullable(false);
            $table->integer('client_id')->nullable(false);
            $table->integer('rent_per_month');
            $table->integer('rent_time');
            $table->integer('waste_percentage');
            $table->integer('profit_percentage');
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
        Schema::drop('feasibility_study');

    }
}
