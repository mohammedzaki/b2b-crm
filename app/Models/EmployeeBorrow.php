<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\EmployeeBorrowCreatedEvent;

class EmployeeBorrow extends Model {

    protected $fillable = [
        'employee_id',
        'amount',
        'borrow_reason',
        'pay_amount',
        'is_active'
    ];
    protected $events = [
        'created' => EmployeeBorrowCreatedEvent::class,
    ];

    public function employee() {
        return $this->belongsTo('App\Models\Employee');
    }

    public function payAmounts() {
        return $this->hasMany(EmployeeBorrowBilling::class);
    }

    public function unPaidAmounts() {
        return $this->hasMany(EmployeeBorrowBilling::class)->where('paying_status', EmployeeBorrowBilling::UN_PAID);
    }

    public function paidAmounts() {
        return $this->hasMany(EmployeeBorrowBilling::class)->where('paying_status', EmployeeBorrowBilling::PAID);
    }

    public function totalPaid() {
        return $this->paidAmounts->sum('paid_amount');
    }

    public function totalRemaining() {
        return $this->payAmounts()->where([
                    ['paying_status', '!=', EmployeeBorrowBilling::POSTPONED]
        ])->sum('pay_amount') - $this->totalPaid();
    }

}
