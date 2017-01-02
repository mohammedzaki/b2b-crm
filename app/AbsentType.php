<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AbsentType extends Model {

    protected $fillable = [
        'name',
        'salary_deduction'
    ];
    
    public function attendances(){
        return $this->hasMany('App\Attendance');
    }

}
