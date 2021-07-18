<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;
use App\Constants\EmployeeActions;
use DB;
use App\Helpers\Helpers;

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
 * @property int|null $working_hours_in_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attendance whereWorkingHoursInSeconds($value)
 */
class Attendance extends Model
{

    public    $workingHours;
    public    $employeeName;
    public    $processName;
    public    $absentTypeName;
    public    $FinancialCustodyValue;
    public    $FinancialCustodyRefundValue;
    public    $borrowValue;
    public    $is_managment_process;
    protected $fillable
        = [
            'date',
            'shift',
            'check_in',
            'check_out',
            'working_hours_in_seconds',
            'absent_check',
            'absent_type_id',
            'salary_deduction',
            'absent_deduction',
            'mokaf',
            'notes',
            'employee_id',
            'process_id'
        ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates
        = [
            //'check_in',
            //'check_out',
            'created_at',
            'updated_at'
        ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function absentType()
    {
        return $this->belongsTo(AbsentType::class);
    }

    public function process()
    {
        return $this->belongsTo(ClientProcess::class);
    }

    public function employeeFinancialCustody()
    {
        $startDate                      = DateTime::parse($this->date)->startOfDay();
        $endDate                        = DateTime::parse($this->date)->endOfDay();
        // $totalFinancialCustodyWithdraws = FinancialCustodyItem::whereBetween('due_date', [$startDate, $endDate])->get()->sum('withdrawValue');

        $totalFinancialCustodyDeposits = DepositWithdraw::where([
                                               ['employee_id', '=', $this->employee_id],
                                               ['expenses_id', '=', EmployeeActions::FinancialCustody],
                                               ['due_date', '>=', $startDate],
                                               ['due_date', '<=', $endDate]
                                           ])->get()->sum('withdrawValue');

        return $totalFinancialCustodyDeposits;
    }

    public function employeeFinancialCustodyRefund()
    {
//        $startDate        = DateTime::parse($this->date)->format('Y-m-d 00:00:00');
//        $endDate          = DateTime::parse($this->date)->format('Y-m-d 23:59:59');
//        $depositWithdraws = DepositWithdraw::where([
//                                                       ['employee_id', '=', $this->employee_id],
//                                                       ['expenses_id', '=', EmployeeActions::FinancialCustodyRefund],
//                                                       ['due_date', '>=', $startDate],
//                                                       ['due_date', '<=', $endDate]
//                                                   ]);
//        try {
//            if ($this->shift == 1) {
//                return $depositWithdraws->sum('depositValue');
//            } else {
//                return 0;
//            }
//        } catch (\Exception $exc) {
//            return 0;
//        }
        $startDate                      = DateTime::parse($this->date)->startOfDay();
        $endDate                        = DateTime::parse($this->date)->endOfDay();
        $totalFinancialCustodyWithdraws = FinancialCustodyItem::whereBetween('due_date', [$startDate, $endDate])->get()->sum('withdrawValue');
        return $totalFinancialCustodyWithdraws;
    }

    public function employeeSmallBorrow()
    {
        $startDate        = DateTime::parse($this->date)->format('Y-m-d 00:00:00');
        $endDate          = DateTime::parse($this->date)->format('Y-m-d 23:59:59');
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

    public function employeeLongBorrow()
    {
        $startDate = DateTime::parse($this->date);

        $employeeBorrowBilling = EmployeeBorrowBilling::select('*')
                                                      ->join('employee_borrows', 'employee_borrow_billing.employee_borrow_id', '=', 'employee_borrows.id')
                                                      ->join('employees', 'employee_borrows.employee_id', '=', 'employees.id')
                                                      ->distinct()
                                                      ->where([
                                                                  ['employee_borrow_billing.paying_status', '!=', EmployeeBorrowBilling::POSTPONED],
                                                                  ['employee_borrow_billing.deposit_id', '=', NULL],
                                                                  ['employees.id', '=', $this->employee_id]
                                                              ])
                                                      ->whereYear('due_date', $startDate->year)
                                                      ->whereMonth('due_date', $startDate->month)
                                                      ->first();
        if (empty($employeeBorrowBilling)) {
            return 0;
        }
        return $employeeBorrowBilling->getRemaining();
    }

    public function workingHoursToString()
    {
        return Helpers::hoursMinutsToString($this->workingHoursToSeconds());
    }

    public function workingHoursToSeconds()
    {
        return Helpers::diffInHoursMinutsToSeconds(DateTime::parse($this->check_in), DateTime::parse($this->check_out));
    }

    public function daySalary()
    {
        return $this->workingHoursToSeconds() * $this->employee->salaryPerSecond();
    }

}
