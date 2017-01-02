<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierProcessItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_process_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->unsigned()->index();
            $table->string('description');
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price')->unsigned();
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
        Schema::drop('supplier_process_items');
    }
}
