<?php

namespace App\Constants;

class EmployeeActions {

    const Guardianship = 1;
    const GuardianshipReturn = 2;
    const SmallBorrow = 3;
    const LongBorrow = 4;
    const TakeSalary = 5;

    public static function all() {
        $employeeActions = [];
        $employeeActions[EmployeeActions::Guardianship] = "عهدة شراء";
        $employeeActions[EmployeeActions::GuardianshipReturn] = "رد عهدة";
        $employeeActions[EmployeeActions::SmallBorrow] = "سلفة";
        $employeeActions[EmployeeActions::LongBorrow] = "سلفة مستديمة";
        //$employeeActions[EmployeeActions::TakeSalary] = "مرتبات";
        return $employeeActions;
    }

}
