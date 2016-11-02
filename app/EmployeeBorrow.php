<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeBorrow extends Model
{
    protected $fillable = [
        'Amount'
    ];

    public function employee(){
        return $this->belongsTo('App\Employee', 'user_id');
    }

}
