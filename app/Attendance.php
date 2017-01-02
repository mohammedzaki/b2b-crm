<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {

    protected $fillable = [
        'date',
        'check_in',
        'check_out',
        'absent_type_id',
        'salary_deduction',
        'absent_deduction',
        'mokaf',
        'notes',
        'employee_id',
        'process_id'
    ];
    public $workingHours;
    public $employeeName;
    public $processName;
    public $absentTypeName;
    public $is_managment_process;

    public function employee(){
        return $this->belongsTo('App\Employee');
    }
    
    public function absentType(){
        return $this->belongsTo('App\AbsentType');
    }
    
    public function process(){
        return $this->belongsTo('App\ClientProcess');
    }

}
