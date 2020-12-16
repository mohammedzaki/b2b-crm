<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FinancialCustodyItem
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
class FinancialCustodyItem extends Model {


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
        'due_date',

        'approved_at',
        'approved_by',
        'daily_cash_id'
    ];


    public function financialCustody() {
        return $this->belongsTo(FinancialCustody::class);
    }

}
