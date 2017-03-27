<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;
use App\Constants\EmployeeActions;
use DB;

class Attendance extends Model {

    protected $fillable = [
        'date',
        'shift',
        'check_in',
        'check_out',
        'working_hours',
        'absent_check',
        'absent_type_id',
        'salary_deduction',
        'absent_deduction',
        'mokaf',
        'notes',
        'employee_id',
        'process_id'
    ];
    public $workingHours;
    public $employeeName;
    public $processName;
    public $absentTypeName;
    public $GuardianshipValue;
    public $GuardianshipReturnValue;
    public $borrowValue;
    public $is_managment_process;

    public function employee() {
        return $this->belongsTo('App\Models\Employee');
    }

    public function absentType() {
        return $this->belongsTo('App\Models\AbsentType');
    }

    public function process() {
        return $this->belongsTo('App\Models\ClientProcess');
    }

    public function employeeGuardianship() {
        $startDate = DateTime::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = DateTime::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                    ['employee_id', '=', $this->employee_id],
                    ['expenses_id', '=', EmployeeActions::Guardianship],
                    ['due_date', '>=', $startDate],
                    ['due_date', '<=', $endDate]
        ]);
        try {
            if ($this->shift == 1) {
                return $depositWithdraws->sum('withdrawValue');
            } else {
                return 0;
            }
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeGuardianshipReturn() {
        $startDate = DateTime::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = DateTime::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                    ['employee_id', '=', $this->employee_id],
                    ['expenses_id', '=', EmployeeActions::GuardianshipReturn],
                    ['due_date', '>=', $startDate],
                    ['due_date', '<=', $endDate]
        ]);
        try {
            if ($this->shift == 1) {
                return $depositWithdraws->sum('depositValue');
            } else {
                return 0;
            }
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeSmallBorrow() {
        $startDate = DateTime::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = DateTime::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                    ['employee_id', '=', $this->employee_id],
                    ['expenses_id', '=', EmployeeActions::SmallBorrow],
                    ['due_date', '>=', $startDate],
                    ['due_date', '<=', $endDate]
                ])->get();
        try {
            return $depositWithdraws[0]->withdrawValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeLongBorrow() {
        $startDate = DateTime::parse($this->date);
        /*
        $depositWithdraws = DepositWithdraw::where([
                    ['employee_id', '=', $this->employee_id],
                    ['expenses_id', '=', EmployeeActions::LongBorrow],
                    ['due_date', '>=', $startDate],
                    ['due_date', '<=', $endDate]
                ])->get();*/
        $employeeBorrowBilling = DB::table('employees')
                ->join('employee_borrows', 'employee_borrows.employee_id', '=', 'employees.id')
                ->join('employee_borrow_billing', 'employee_borrow_billing.employee_borrow_id', '=', 'employee_borrows.id')
                ->distinct()
                ->where([
                    ['is_paid', '=', FALSE],
                    ['employees.id', '=', $this->employee_id]
                ])
                ->whereMonth('due_date', $startDate->month)
                ->get();
        try {
            return $employeeBorrowBilling[0]->pay_amount;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function diffInHoursMinutsToString($startDate, $endDate) {
        $totalDuration = $endDate->diffInSeconds($startDate);
        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration / 60) % 60);
        $seconds = $totalDuration % 60;

        return "$hours:$minutes:$seconds";
    }

    public function workingHoursToString() {
        $totalDuration = $this->workingHoursToSeconds();
        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration / 60) % 60);
        $seconds = $totalDuration % 60;

        return "$hours:$minutes:$seconds";
    }

    public function workingHoursToSeconds() {
        $check_out = DateTime::parse($this->check_out);
        $check_in = DateTime::parse($this->check_in);

        $totalDuration = $check_out->diffInSeconds($check_in);
        return $totalDuration;
    }

}
