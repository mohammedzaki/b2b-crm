<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = new Permission();
        $permission->name = 'facility-info';
        $permission->display_name = 'بيانات المنشأة';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'employees-permissions';
        $permission->display_name = 'صلاحيات الموظفين';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'attendance';
        $permission->display_name = 'حضور وانصراف';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'new-process-client';
        $permission->display_name = 'عملية جديدة عميل';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'new-process-supplier';
        $permission->display_name = 'عملية جديدة مورد';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'new-client';
        $permission->display_name = 'عميل جديد';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'new-supplier';
        $permission->display_name = 'مورد جديد';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'new-outgoing';
        $permission->display_name = 'مصروف جديد';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'query-client';
        $permission->display_name = 'استعلام عميل';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'query-supplier';
        $permission->display_name = 'استعلام مورد';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'query-cost-center';
        $permission->display_name = 'استعلام مركز تكلفة';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'query-invoice';
        $permission->display_name = 'استعلام عن فاتورة';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'database';
        $permission->display_name = 'قاعدة بيانات';
        $permission->save();
    }
}
