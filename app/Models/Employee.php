<?php

namespace App\Models;

use App\Constants\EmployeeActions;
use App\Extensions\DateTime;
use App\Models\DepositWithdraw;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Employee extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'emp_id',
        'ssn',
        'gender',
        'martial_status',
        'birth_date',
        'department',
        'hiring_date',
        'daily_salary',
        'working_hours',
        'job_title',
        'telephone',
        'mobile',
        'facility_id',
        'can_not_use_program',
        'borrow_system',
        'deleted_at_id'
    ];
    
    public $username = "";
    public $password = "";

    public function users() {
        return $this->hasMany('App\Models\User', 'employee_id');
    }

    public function smallBorrows() {
        DepositWithdraw::where([
                ['employee_id', '=', $this->id],
                ['expenses_id', '=', EmployeeActions::SmallBorrow],
        ]);
    }
    
    public function longBorrows() {
        return $this->hasMany(EmployeeBorrow::class);
    }
    
    public function hasUnpaidBorrow() {
        $count = EmployeeBorrow::join('employee_borrow_billing', 'employee_borrows.id', '=', 'employee_borrow_billing.employee_borrow_id')
                ->select('employee_borrow_billing.*')->where([
                    ['employee_borrow_billing.paying_status', "=", EmployeeBorrowBilling::UN_PAID],
                    ['employee_borrows.employee_id', '=', $this->id]])->count();
        if ($count > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function totalUnpaidBorrow() {
        $total = EmployeeBorrow::join('employee_borrow_billing', 'employee_borrows.id', '=', 'employee_borrow_billing.employee_borrow_id')
                ->select('employee_borrow_billing.*')
                ->where([
                    ['employee_borrow_billing.paying_status', "=", EmployeeBorrowBilling::UN_PAID],
                    ['employee_borrows.employee_id', '=', $this->id]]);
        $total = $total->sum('employee_borrow_billing.pay_amount') - $total->sum('employee_borrow_billing.paid_amount');
        return empty($total) ? 0 : $total;
    }
    
    public function unpaidBorrows() { //
        $borrows = EmployeeBorrowBilling::join('employee_borrows', 'employee_borrows.id', '=', 'employee_borrow_billing.employee_borrow_id')
                ->select('employee_borrow_billing.*')->where([
                    ['employee_borrow_billing.paying_status', "=", EmployeeBorrowBilling::UN_PAID],
                    ['employee_borrows.employee_id', '=', $this->id]])->get();
        return $borrows;
    }

    public function employeeGuardianships(DateTime $dt) {
        $startDate = DateTime::parse($dt)->startOfMonth();
        $endDate = DateTime::parse($dt)->endOfMonth();
        $depositWithdraws = DB::select("SELECT distinct dw.* from deposit_withdraws as dw
JOIN employees emp ON dw.employee_id = emp.id
WHERE emp.id = {$this->id}
AND dw.expenses_id in (" . EmployeeActions::Guardianship . ", " . EmployeeActions::GuardianshipReturn . ") 
AND 
((dw.due_date BETWEEN '{$startDate->startDayFormat()}' and '{$endDate->endDayFormat()}' AND dw.notes is null)
OR dw.notes BETWEEN '{$startDate->startDayFormat()}' and '{$endDate->endDayFormat()}')");

        return $depositWithdraws;
    }

    public function lastGuardianship() {
        try {
            $depositWithdraws = DepositWithdraw::where([
                            ['employee_id', '=', $this->id],
                            ['expenses_id', '=', EmployeeActions::Guardianship]
                    ])->orderBy('id', 'desc')
                    ->first();
            return $depositWithdraws->withdrawValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function lastGuardianshipReturn() {
        try {
            $depositWithdraws = DepositWithdraw::where([
                            ['employee_id', '=', $this->id],
                            ['expenses_id', '=', EmployeeActions::GuardianshipReturn]
                    ])->orderBy('id', 'desc')
                    ->first();
            return $depositWithdraws->depositValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function lastGuardianshipId() {
        try {
            $depositWithdraws = DepositWithdraw::where([
                            ['employee_id', '=', $this->id],
                            ['expenses_id', '=', EmployeeActions::Guardianship]
                    ])->orderBy('id', 'desc')
                    ->first();
            return $depositWithdraws->id;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function lastGuardianshipReturnId() {
        try {
            $depositWithdraws = DepositWithdraw::where([
                            ['employee_id', '=', $this->id],
                            ['expenses_id', '=', EmployeeActions::GuardianshipReturn]
                    ])->orderBy('id', 'desc')
                    ->first();
            return $depositWithdraws->id;
        } catch (\Exception $exc) {
            return 0;
        }
    }

}
