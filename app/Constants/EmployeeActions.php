<?php

namespace App\Constants;

class EmployeeActions {

    const Guardianship = 1;
    const GuardianshipReturn = 2;
    const SmallBorrow = 3;
    const LongBorrow = 4;
    const TakeSalary = 5;

    public static function all() {
        $employeeActions = [
            ['id' => EmployeeActions::Guardianship, 'name' => "عهدة شراء"],
            ['id' => EmployeeActions::GuardianshipReturn, 'name' => "رد عهدة"],
            ['id' => EmployeeActions::SmallBorrow, 'name' => "سلفة"],
            ['id' => EmployeeActions::LongBorrow, 'name' => "سلفة مستديمة"]
        ];
        return $employeeActions;
    }

}
