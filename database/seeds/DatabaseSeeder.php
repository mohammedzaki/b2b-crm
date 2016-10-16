<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(FacilitySeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ClientProcessSeeder::class);
    }
}
