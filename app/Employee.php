<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	protected $primaryKey = 'user_id';
	public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function employeeBorrow(){
        return $this->hasMany('App\EmployeeBorrow', 'user_id');
    }
}
