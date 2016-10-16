<?php

use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facilities')->insert([
            'name' => 'B2bAdv',
            'manager_id' => 1,
            'type' => 'stock',

            'tax_file' => '11200',
            'tax_card' => '23000',
            'trade_record' => '98100',
            'sales_tax' => '654510',

            'logo' => 'Q254GxNKrjKryjnN46zRKgloE00ID0DkGvmDajL6.png',

            'country' => 'مصر',
            'city' => 'القاهرة',
            'region' => 'عين شمس',
            'address' => '12 ش محمد متولي',
            'website' => 'http://www.b2b-adv.com/',
            'email' => 'info@b2b-adv.com'
        ]);

        
    }
}
