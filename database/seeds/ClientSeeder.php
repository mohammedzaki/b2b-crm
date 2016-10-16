<?php

use Illuminate\Database\Seeder;
use App\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $client->name = 'بي تك';
        $client->address = '25 شارع جوزيف تيتو النزهة الجديدة';
        $client->telephone = '26211222';
        $client->mobile = '01200000123';
        $client->referral_id = '3';
        $client->referral_percentage = '2';
        $client->credit_limit = '20000';
        $client->is_client_company = false;
        // $client->authorized_name = 'احمد محمد حسن';
        // $client->authorized_jobtitle = 'رئيس قسم اﻻجهزة';
        // $client->authorized_telephone = '012000000087';
        // $client->authorized_email = 'btech@btech.com';
        $client->save();
    }
}
