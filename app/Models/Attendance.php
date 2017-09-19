<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;
use App\Constants\EmployeeActions;
use DB;

/**
 * App\Models\Attendance
 *
 * @property int $id
 * @property string|null $date
 * @property int|null $shift
 * @property string|null $check_in
 * @property string|null $check_out
 * @property int|null $absent_check
 * @property int|null $absent_type_id
 * @property float|null $absent_deduction
 * @property float|null $salary_deduction
 * @property float|null $mokaf
 * @property string|null $notes
 * @property int|null $employee_id
 * @property int|null $process_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\AbsentType|null $absentType
 * @property-read \App\Models\Employee|null $employee
 * @property-read \App\Models\ClientProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereAbsentCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereAbsentDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereAbsentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereMokaf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereSalaryDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        $startDate = DateTime::parse($this->date)->startOfDay(); //->format('Y-m-d 00:00:00');
        $endDate = DateTime::parse($this->date)->endOfDay(); //->format('Y-m-d 23:59:59');
        $depositWithdraws = DB::select("SELECT distinct dw.* from deposit_withdraws as dw
JOIN employees emp ON dw.employee_id = emp.id
WHERE emp.id = {$this->employee_id}
AND dw.expenses_id = " . EmployeeActions::Guardianship . " 
AND 
((dw.due_date BETWEEN '{$startDate->startDayFormat()}' and '{$endDate->endDayFormat()}' AND dw.notes is null)
OR dw.notes BETWEEN '{$startDate->startDayFormat()}' and '{$endDate->endDayFormat()}')");

        try {
            //if ($this->shift == 1) {
            $withdrawValue = 0;
            foreach ($depositWithdraws as $key => $value) {
                $withdrawValue += $value->withdrawValue;
            }
            return $withdrawValue;
            //} else {
            //    return 0;
            //}
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
                ]);
        try {
            return $depositWithdraws->sum('withdrawValue');
        } catch (\Exception $exc) {
            return 0;
        }
    }

    public function employeeLongBorrow() {
        $startDate = DateTime::parse($this->date);
        $employeeBorrowBilling = DB::table('employees')
                ->join('employee_borrows', 'employee_borrows.employee_id', '=', 'employees.id')
                ->join('employee_borrow_billing', 'employee_borrow_billing.employee_borrow_id', '=', 'employee_borrows.id')
                ->distinct()
                ->where([
                    ['employee_borrow_billing.paying_status', '=', EmployeeBorrowBilling::UN_PAID],
                    ['employees.id', '=', $this->employee_id]
                ])
                ->whereMonth('due_date', $startDate->month)
                ->get();
        try {
            //FIXME: getRemaining() not a function from object 
            //return $employeeBorrowBilling[0]->getRemaining();
            return $employeeBorrowBilling[0]->pay_amount - $employeeBorrowBilling[0]->paid_amount;
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
