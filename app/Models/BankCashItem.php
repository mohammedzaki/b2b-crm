<?php

namespace App\Models;

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
class BankCashItem extends Model
{
    use SoftDeletes;
    protected $table = "bank_cashes";
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
        'bank_profile_id',
        'cheque_book_id',
        
        'user_id',
        'issuing_date',
        'cashing_date',
        'cheque_number',
        'cheque_status',
        'cheque_notes',
        'due_date'
    ];


    public function items()
    {
        return $this->hasMany('App\Models\ClientProcessItem', 'process_id')->withTrashed();
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id')->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id')->withTrashed();
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id')->withTrashed();
    }

    public function expenses()
    {
        return $this->belongsTo('App\Models\Expenses', 'expenses_id')->withTrashed();
    }

    public function bank()
    {
        return $this->belongsTo(BankProfile::class, 'bank_profile_id')->withTrashed();
    }

    /**
     * @param bool $addFor
     * @return string
     */
    public function getDescription($addFor = true)
    {
        $for = '';
        if ($addFor) {
            if (isset($this->client_id)) {
                $for = ", عميل: {$this->client->name}";
            } elseif (isset($this->supplier_id)) {
                $for = ", مورد: {$this->supplier->name}";
            }
        }
        return " شيك رقم: {$this->cheque_number}, {$this->bank->name}, {$this->recordDesc} {$for}";
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return isset($this->withdrawValue) ? "شيك منصرف بقيمة:{$this->withdrawValue}" : "شيك وارد بقيمة: {$this->depositValue}";
    }
}
