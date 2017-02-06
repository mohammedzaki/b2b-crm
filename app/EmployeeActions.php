<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\DepositWithdraw;

class EmployeeActions {
    /*
      $employeeActions[1] = "عهدة";
      $employeeActions[2] = "رد عهدة";
      $employeeActions[3] = "سلفة";
      $employeeActions[4] = "سلفة مستديمة";
     *      */

    const Guardianship = 1;
    const GuardianshipReturn = 2;
    const SmallBorrow = 3;
    const LongBorrow = 4;

}
