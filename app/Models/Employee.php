<?php

namespace App\Models;

use App\Constants\EmployeeActions;
use App\Extensions\DateTime;
use App\Models\DepositWithdraw;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

/**
 * App\Models\Employee
 *
 * @property int $id
 * @property int|null $emp_id
 * @property string $name
 * @property string $ssn
 * @property string $gender
 * @property string $martial_status
 * @property string $birth_date
 * @property string $department
 * @property string $hiring_date
 * @property float $daily_salary
 * @property int $working_hours
 * @property string $job_title
 * @property string|null $telephone
 * @property string $mobile
 * @property int $facility_id
 * @property int $can_not_use_program
 * @property int $borrow_system
 * @property int|null $deleted_at_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeBorrow[] $longBorrows
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $smallBorrows
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Employee onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereBorrowSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereCanNotUseProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereDailySalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereDeletedAtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereEmpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereHiringDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereMartialStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereSsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereWorkingHours($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Employee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Employee withoutTrashed()
 * @mixin \Eloquent
 */
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
        return $this->hasMany(User::class, 'employee_id');
    }

    public function smallBorrows() {
        return $this->hasMany(DepositWithdraw::class, 'employee_id', 'id')->where('expenses_id', EmployeeActions::SmallBorrow);
    }
    
    public function totalSmallBorrows() {
        return $this->smallBorrows()->sum('withdrawValue');
    }
    
    public function longBorrows() {
        return $this->hasMany(EmployeeBorrow::class);
    }
    
    public function totalLongBorrows() {
        return $this->longBorrows()->sum('amount');
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
    
    public function totalPaidBorrow() {
        $total = EmployeeBorrow::join('employee_borrow_billing', 'employee_borrows.id', '=', 'employee_borrow_billing.employee_borrow_id')
                ->select('employee_borrow_billing.*')
                ->where([
                    ['employee_borrow_billing.paying_status', "=", EmployeeBorrowBilling::PAID],
                    ['employee_borrows.employee_id', '=', $this->id]]);
        $total = $total->sum('employee_borrow_billing.paid_amount');
        return empty($total) ? 0 : $total;
    }
    
    public function unpaidBorrows() { //
        $borrows = EmployeeBorrowBilling::join('employee_borrows', 'employee_borrows.id', '=', 'employee_borrow_billing.employee_borrow_id')
                ->select('employee_borrow_billing.*')->where([
                    ['employee_borrow_billing.paying_status', "=", EmployeeBorrowBilling::UN_PAID],
                    ['employee_borrows.employee_id', '=', $this->id]])->get();
        return $borrows;
    }

    public function salaryPerHour() {
        return $this->daily_salary / $this->working_hours;
    }
    
    public function salaryPerMinute() {
        return $this->salaryPerHour() / 60;
    }
    
    public function salaryPerSecond() {
        return $this->salaryPerMinute() / 60;
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
