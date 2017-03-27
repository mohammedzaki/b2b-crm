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
        'due_date',
        'is_paid'
    ];

    public function borrow() {
        return $this->belongsTo(EmployeeBorrow::class);
    }

}
