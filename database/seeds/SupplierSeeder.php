<?php

use Illuminate\Database\Seeder;
use App\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $supplier = new Supplier();
        $supplier->name = 'أورسكوم';
        $supplier->address = '13 شارع الﻻسلكي المعادي';
        $supplier->telephone = '26211222';
        $supplier->mobile = '01200000123';
        $supplier->debit_limit = '50000';
        $supplier->save();
    }
}
