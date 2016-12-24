<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeBorrow extends Model
{
    protected $fillable = [
        'user_id',
        'Amount',
        'borrow_reason',
        'pay_amount',
        'is_active'
    ];

    public function employee(){
        return $this->belongsTo('App\Employee', 'user_id');
    }

}
