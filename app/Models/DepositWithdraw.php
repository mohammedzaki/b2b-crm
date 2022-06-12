<?php

namespace App\Models;

use App\Extensions\DateTime;
use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\DepositWithdraw
 *
 * @property int $id
 * @property float|null $depositValue
 * @property float|null $withdrawValue
 * @property string|null $recordDesc
 * @property int|null $cbo_processes
 * @property int|null $client_id
 * @property int|null $employee_id
 * @property int|null $supplier_id
 * @property int|null $expenses_id
 * @property int|null $payMethod
 * @property string|null $notes
 * @property int|null $saveStatus 1: can edit, 2: can not edit
 * @property string|null $due_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\Employee $employee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeBorrowBilling[] $employeeLogBorrowBillings
 * @property-read \App\Models\Expenses $expenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcessItem[] $items
 * @property-read \App\Models\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereCboProcesses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereDepositValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereExpensesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw wherePayMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereRecordDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereSaveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DepositWithdraw whereWithdrawValue($value)
 * @mixin \Eloquent
 */
class DepositWithdraw extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at', 'due_date'];
    protected $fillable = [
        'depositValue',
        'withdrawValue',
        'recordDesc',
        
        'cbo_processes',
        'client_id',
        'employee_id',
        'supplier_id',
        'expenses_id',
        'financial_custody_id',

        'user_id',
        'payMethod',
        'notes',
        'due_date'
    ];


    public function items() {
        return $this->hasMany('App\Models\ClientProcessItem', 'process_id');
    }

    public function employee() {
        return $this->hasOne('App\Models\Employee', 'id');
    }
    
    public function client() {
        return $this->hasOne('App\Models\Client', 'id');
    }
    
    public function supplier() {
        return $this->hasOne('App\Models\Supplier', 'id');
    }

    public function expenses() {
        return $this->hasOne('App\Models\Expenses', 'id');
    }
    
    public function employeeLogBorrowBillings() {
        return $this->hasMany(EmployeeBorrowBilling::class, 'deposit_id', 'id');
    }

    public static function getDWItems($startDate = '2000-01-01 00:00:00', $endDate = '2099-01-01 00:00:00')
    {
        return static::whereBetween('due_date', [$startDate, $endDate])->get();
    }

    public static function calculateCurrentAmount($endDate = '2099-01-01 00:00:00', $startDate = '2000-01-01 00:00:00')
    {
        $depositWithdrawsItems = static::getDWItems($startDate, $endDate);
        $depositValue          = $depositWithdrawsItems->sum('depositValue');
        $withdrawValue         = $depositWithdrawsItems->sum('withdrawValue');
        $openingAmount         = OpeningAmount::whereBetween('deposit_date', [$startDate, $endDate])->sum('amount');
        $loansAmount         = Loans::whereBetween('date', [$startDate, $endDate])
            ->where('save_id', '=', 0)->sum('amount');
        return round(($depositValue + $openingAmount + $loansAmount) - $withdrawValue, Helpers::getDecimalPointCount());
    }
}
