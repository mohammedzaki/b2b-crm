<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DepositWithdraw;
use App\Constants\EmployeeActions;

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
        //return $this->belongsTo('App\Models\User', 'employee_id');
        return $this->hasMany('App\Models\User', 'employee_id');
    }

    /* public function employeeBorrow() {
      return $this->hasMany('App\Models\EmployeeBorrow', 'id');
      } */

    public function employeeSmallBorrows() {
        DepositWithdraw::where([
                ['employee_id', '=', $this->id],
                ['expenses_id', '=', EmployeeActions::SmallBorrow],
        ]); //->get();
    }

    public function employeeGuardianships($month) {
        //$startDate = Carbon::parse($this->date)->format('Y-m-d 00:00:00');
        //$endDate = Carbon::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                                ['employee_id', '=', $this->id]
                        ])
                        ->whereIn('expenses_id', [EmployeeActions::Guardianship, EmployeeActions::GuardianshipReturn])
                        ->whereMonth('due_date', '=', $month)->get();

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
