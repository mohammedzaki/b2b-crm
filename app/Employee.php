<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name', 
        'emp_id',
        'ssn',
        'gender',
        'martial_status', 
        'birth_date', 
        'department', 
        'hiring_date', 
        'daily_salary', 
        'working_hours', 
        'job_title', 
        'telephone', 
        'mobile', 
        'facility_id', 
        'can_not_use_program',
        'is_active',
        'borrow_system',
        'deleted_at_id'
    ];

    /* public function user() {
      return $this->belongsTo('App\User', 'user_id');
      } */

    /* public function employeeBorrow() {
      return $this->hasMany('App\EmployeeBorrow', 'user_id');
      } */
}
