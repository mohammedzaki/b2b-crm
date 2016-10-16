<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'beshoy',
            'email' => 'info@b2b-adv.com',
            'password' => bcrypt('Mr.Beshoyb2b'),
        ]);

        DB::table('users')->insert([
            'username' => 'abanoub',
            'email' => 'abanoub.adelb@gmail.com',
            'password' => bcrypt('@b@n0ub'),
        ]);

        DB::table('users')->insert([
            'username' => 'test',
            'email' => 'test@b2b-adv.com',
            'password' => bcrypt('t3st!000'),
        ]);

        DB::table('employees')->insert([
            'user_id' => '1',
            'name' => 'استاذ بيشوي',
            'ssn' => '28100010500755',
            'gender' => 'm',
            'martial_status' => 'married',
            'daily_salary' => '200',
            'birth_date' => '1980-02-09',
            'hiring_date' => '2001-01-01',
            'working_hours' => '8',
            'job_title' => 'مدير',
            'mobile' => '01200000000',
            'facility_id' => 1,
            'can_not_use_program' => false,
            'is_active' => true,
            'borrow_system' => false
        ]);

        DB::table('employees')->insert([
            'user_id' => '2',
            'name' => 'ابانوب عادل',
            'ssn' => '29100010500789',
            'gender' => 'm',
            'martial_status' => 'single',
            'daily_salary' => '100',
            'birth_date' => '1991-08-05',
            'hiring_date' => '2016-06-01',
            'working_hours' => '7',
            'job_title' => 'مهندس',
            'mobile' => '01203399927',
            'facility_id' => 1,
            'can_not_use_program' => false,
            'is_active' => true,
            'borrow_system' => false
        ]);

        DB::table('employees')->insert([
            'user_id' => '3',
            'name' => 'محمد فوزري',
            'ssn' => '29100010500789',
            'gender' => 'm',
            'martial_status' => 'single',
            'daily_salary' => '60',
            'birth_date' => '1984-05-28',
            'hiring_date' => '2016-03-01',
            'working_hours' => '5',
            'job_title' => 'مندوب',
            'mobile' => '01256472300',
            'facility_id' => 1,
            'can_not_use_program' => false,
            'is_active' => true,
            'borrow_system' => false
        ]);

        $abanoub = App\User::where('username', '=', 'abanoub')->first();
        $beshoy = App\User::where('username', '=', 'beshoy')->first();
        $test = App\User::where('username', '=', 'test')->first();

        $admin = new App\Role();
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->save();
        $abanoub->attachRole($admin);
        $beshoy->attachRole($admin);

        $role3 = new App\Role();
        $role3->name = 'role_3';
        $role3->save();
        $test->attachRole($role3);

    }
}
