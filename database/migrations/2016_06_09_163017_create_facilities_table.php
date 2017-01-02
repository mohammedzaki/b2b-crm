<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('manager_id')->unsigned()->index();
            $table->enum('type', [
                'individual',
                'joint', 
                'partnership',
                'limited_partnership',
                'stock'
            ]);
            $table->string('tax_file')->nullable();
            $table->string('tax_card')->nullable();
            $table->string('trade_record')->nullable();
            $table->string('sales_tax')->nullable();
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
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
        Schema::drop('facilities');
    }
}
