<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\DepositWithdraw;

class EmployeeActions {

    const Guardianship = 1;
    const GuardianshipReturn = 2;
    const SmallBorrow = 3;
    const LongBorrow = 4;

    public static function all() {
        $employeeActions = [];
        $employeeActions[EmployeeActions::Guardianship] = "عهدة شراء";
        $employeeActions[EmployeeActions::GuardianshipReturn] = "رد عهدة";
        $employeeActions[EmployeeActions::SmallBorrow] = "سلفة";
        $employeeActions[EmployeeActions::LongBorrow] = "سلفة مستديمة";
        return $employeeActions;
    }
}
