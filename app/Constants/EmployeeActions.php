<?php

namespace App\Constants;

class EmployeeActions {

    const FinancialCustody = 1;
    const FinancialCustodyRefund = 2;
    const SmallBorrow = 3;
    const LongBorrow = 4;
    const TakeSalary = 5;
    const PayLongBorrow = 6;

    public static function all() {
        return [
            ['id' => EmployeeActions::FinancialCustody, 'name' => "عهدة شراء"],
            ['id' => EmployeeActions::FinancialCustodyRefund, 'name' => "رد عهدة"],
            ['id' => EmployeeActions::SmallBorrow, 'name' => "سلفة"],
            ['id' => EmployeeActions::LongBorrow, 'name' => "سلفة مستديمة"],
            ['id' => EmployeeActions::PayLongBorrow, 'name' => "سداد سلفة مستديمة"]
        ];
    }

}
