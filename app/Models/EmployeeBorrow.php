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

}
