<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            //$table->integer('id')->unsigned()->index();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('ssn', 14);
            $table->enum('gender', ['m', 'f']);
            $table->enum('martial_status', ['single', 'married', 'widowed', 'divorced']);
            $table->date('birth_date');
            $table->string('department');
            $table->date('hiring_date');
            $table->double('daily_salary');
            $table->integer('working_hours');
            $table->string('job_title', 50);
            $table->string('telephone', 14)->nullable();
            $table->string('mobile', 14);
            $table->integer('facility_id')->unsigned()->index();
            $table->boolean('can_not_use_program');
            $table->boolean('is_active');
            $table->boolean('borrow_system');
            // $table->foreign('facility_id')->references('id')->on('facilities');
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
        Schema::drop('employees');
    }
}
