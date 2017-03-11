<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBorrow extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'borrow_reason',
        'pay_amount',
        'is_active'
    ];

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }

}
