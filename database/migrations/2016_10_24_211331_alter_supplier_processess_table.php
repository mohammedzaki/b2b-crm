<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSupplierProcessessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_processes', function (Blueprint $table) {
            $table->string('name');
            $table->integer('supplier_id')->unsigned()->index();
            $table->integer('employee_id')->unsigned()->index();
            $table->enum('status', ['active', 'temporary_closed', 'closed']);
            $table->string('notes')->nullable();
            $table->boolean('has_discount')->nullable();
            $table->decimal('discount_percentage')->nullable();
            $table->string('discount_reason')->nullable();
            $table->boolean('require_bill');
            $table->decimal('total_price');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_processes', function (Blueprint $table) {
            //
        });
    }
}
