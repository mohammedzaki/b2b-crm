<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmployeeBorrowBilling
 *
 * @property int $id
 * @property int|null $employee_borrow_id
 * @property float|null $pay_amount
 * @property float|null $paid_amount
 * @property string|null $paid_date
 * @property string|null $due_date
 * @property int|null $deposit_id
 * @property int|null $paying_status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\EmployeeBorrow $borrow
 * @property-read \App\Models\DepositWithdraw|null $deposit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling whereDepositId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling whereEmployeeBorrowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling wherePaidDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling wherePayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling wherePayingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrowBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmployeeBorrowBilling extends Model {

    /**
     * Generated
     */
    protected $table = 'employee_borrow_billing';
    protected $fillable = [
        'employee_borrow_id',
        'pay_amount',
        'paid_amount',
        'deposit_id',
        'due_date',
        'paid_date',
        'paying_status'
    ];

    const UN_PAID = 0;
    const PAID = 1;
    const POSTPONED = 2;

    public function borrow() {
        return $this->belongsTo(EmployeeBorrow::class);
    }
    
    public function deposit() {
        return $this->belongsTo(DepositWithdraw::class, 'deposit_id', 'id');
    }
    
    public function getRemaining() {
        return $this->pay_amount - $this->paid_amount;
    } 
    
    public function getStatus() {
        switch ($this->paying_status) {
            case static::PAID:
                if (empty($this->deposit_id)) {
                    return 'تم الدفع من المرتب';
                } else {
                    return 'تم الدفع من الوارد و المنصرف';
                }
            case static::UN_PAID:
                return 'لم يتم الدفع';
            case static::POSTPONED:
                return 'تم الترحيل';
        }
    }

}
