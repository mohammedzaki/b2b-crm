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

    public function employeeSmallBorrows() {
        DepositWithdraw::where([
                ['employee_id', '=', $this->id],
                ['expenses_id', '=', EmployeeActions::SmallBorrow],
        ]);
    }

    public function employeeGuardianships(DateTime $dt) {
        /*$depositWithdraws = DepositWithdraw::where([
                                ['employee_id', '=', $this->id]
                        ])
                        ->whereIn('expenses_id', [EmployeeActions::Guardianship, EmployeeActions::GuardianshipReturn])
                        ->whereMonth('due_date', '=', $month)->get();*/
        
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
