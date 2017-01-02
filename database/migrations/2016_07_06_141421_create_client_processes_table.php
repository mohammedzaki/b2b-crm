<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_processes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('client_id')->unsigned()->index();
            $table->integer('employee_id')->unsigned()->index();
            $table->enum('status', ['active', 'temporary_closed', 'closed']);
            $table->string('notes')->nullable();
            $table->boolean('has_discount')->nullable();
            $table->decimal('discount_percentage')->nullable();
            $table->string('discount_reason')->nullable();
            $table->boolean('require_bill');
            $table->decimal('total_price');
            $table->softDeletes();
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
        Schema::drop('client_processes');
    }
}
