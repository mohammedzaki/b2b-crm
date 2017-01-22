<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model {

    protected $fillable = [
        'date',
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
        return $this->belongsTo('App\Employee');
    }

    public function absentType() {
        return $this->belongsTo('App\AbsentType');
    }

    public function process() {
        return $this->belongsTo('App\ClientProcess');
    }

    public function employeeGuardianship() {
        $startDate = Carbon::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                        ['employee_id', '=', $this->employee_id],
                        ['expenses_id', '=', 1],
                        ['created_at', '>=', $startDate],
                        ['created_at', '<=', $endDate]
                ])->get();
        try {
            return $depositWithdraws[0]->withdrawValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeGuardianshipReturn() {
        $startDate = Carbon::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                        ['employee_id', '=', $this->employee_id],
                        ['expenses_id', '=', 2],
                        ['created_at', '>=', $startDate],
                        ['created_at', '<=', $endDate]
                ])->get();
        try {
            return $depositWithdraws[0]->depositValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeSmallBorrow() {
        $startDate = Carbon::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                        ['employee_id', '=', $this->employee_id],
                        ['expenses_id', '=', 3],
                        ['created_at', '>=', $startDate],
                        ['created_at', '<=', $endDate]
                ])->get();
        try {
            return $depositWithdraws[0]->withdrawValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeLongBorrow() {
        $startDate = Carbon::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($this->date)->format('Y-m-d 23:59:59');
        $depositWithdraws = DepositWithdraw::where([
                        ['employee_id', '=', $this->employee_id],
                        ['expenses_id', '=', 4],
                        ['created_at', '>=', $startDate],
                        ['created_at', '<=', $endDate]
                ])->get();
        try {
            return $depositWithdraws[0]->withdrawValue;
        } catch (\Exception $exc) {
            return 0;
        }
    }
    
    public function diffInHoursMinutsToString($startDate, $endDate) {
        $totalDuration = $endDate->diffInSeconds($startDate);

        return gmdate('H:i:s', $totalDuration);
    }
    
    public function workingHoursToString() {
        $check_out = Carbon::parse($this->check_out);
        $check_in = Carbon::parse($this->check_in);
        $totalDuration = $check_out->diffInSeconds($check_in);
        return gmdate('H:i:s', $totalDuration);
    }
    
    public function workingHoursToSeconds() {
        $check_out = Carbon::parse($this->check_out);
        $check_in = Carbon::parse($this->check_in);
        $totalDuration = $check_out->diffInSeconds($check_in);
        return $totalDuration;
    }

}
