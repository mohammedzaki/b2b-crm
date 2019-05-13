<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\EmployeeJobProfile
 *
 */
class EmployeeJobProfile extends Model
{

    use SoftDeletes;

    protected $dates    = ['deleted_at'];
    protected $fillable = [
        'id',
        'start_date',
        'end_date',
        'job_title',
        'working_hours',
        'daily_salary',
        'employee_id'
    ];

    public function getLast() {
        return static::all()->get(count(static::all()) - 2);
    }
    
}
