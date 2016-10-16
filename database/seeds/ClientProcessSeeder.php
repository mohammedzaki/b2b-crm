<?php

use Illuminate\Database\Seeder;
use App\ClientProcess;
use App\ClientProcessItem;

class ClientProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $process = new ClientProcess();
        $process->name = 'عملية جديدة';
        $process->client_id = 1;
        $process->employee_id = 1;
        $process->status = 'active';
        $process->notes = 'ﻻ يوجد';
        $process->has_discount = false;
        $process->require_bill = false;
        $process->total_price = 20000;
        $process->save();

        $item1 = new ClientProcessItem();
        $item1->process_id = 1;
        $item1->description = "بيان 1";
        $item1->quantity = 2500;
        $item1->unit_price = 2;
        $item1->save();

        $item2 = new ClientProcessItem();
        $item2->process_id = 1;
        $item2->description = "بيان 2";
        $item2->quantity = 2500;
        $item2->unit_price = 2;
        $item2->save();

        $item3 = new ClientProcessItem();
        $item3->process_id = 1;
        $item3->description = "بيان 3";
        $item3->quantity = 2500;
        $item3->unit_price = 2;
        $item3->save();

        $item4 = new ClientProcessItem();
        $item4->process_id = 1;
        $item4->description = "بيان 4";
        $item4->quantity = 2500;
        $item4->unit_price = 2;
        $item4->save();
    }
}
