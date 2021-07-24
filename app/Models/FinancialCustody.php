<?php

namespace App\Models;

use App\Constants\EmployeeActions;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DepositWithdraw
 *
 * @property int $id
 * @property float|null $depositValue
 * @property float|null $withdrawValue
 * @property string|null $description
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $deposits
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
class FinancialCustody extends Model {

    protected $dates = ['created_at', 'updated_at', 'approved_at'];

    protected $fillable = [
        'amount',
        'description',
        'notes',
        'due_date',
        'user_id',
        'employee_id',
        'approved_by',
        'approved_at',
        'updated_at',
        'created_at'
    ];


    public function deposits() {
        return $this->hasMany(DepositWithdraw::class, 'financial_custody_id')->where('expenses_id', EmployeeActions::FinancialCustody);
    }

    public function totalDeposits() {
        return $this->deposits()->sum('withdrawValue');
    }

    public function refundedDeposits() {
        return $this->hasMany(DepositWithdraw::class, 'financial_custody_id')->where('expenses_id', EmployeeActions::FinancialCustodyRefund);
    }

    public function totalRefundedDeposits() {
        return $this->refundedDeposits()->sum('depositValue');
    }

    public function withdraws() {
        return $this->hasMany(FinancialCustodyItem::class, 'financial_custody_id');
    }

    public function totalWithdraws() {
        return $this->withdraws()->sum('withdrawValue');
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function approved_by_data() {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }

    public function hasNotApprovedItems() {
        $financialCustodyItems = $this->hasMany(FinancialCustodyItem::class, 'financial_custody_id')->get();
        foreach ($financialCustodyItems as $financialCustodyItem) {
            if (!isset($financialCustodyItem->approved_at)) {
                return true;
            }
        }
        return false;
    }

}
