<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
