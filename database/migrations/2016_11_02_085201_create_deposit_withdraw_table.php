<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositWithdrawTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('deposit_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('depositValue');
            $table->decimal('withdrawValue');
            $table->string('recordDesc');
            $table->integer('cbo_processes');
            $table->integer('client_id');
            $table->integer('employee_id');
            $table->integer('supplier_id');
            $table->integer('expenses_id');
            $table->integer('payMethod');
            $table->string('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('deposit_withdraws');
    }

}
