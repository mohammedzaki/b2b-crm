<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AbsentType
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $salary_deduction
 * @property int|null $editable_deduction
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendances
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AbsentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AbsentType whereEditableDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AbsentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AbsentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AbsentType whereSalaryDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AbsentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AbsentType extends Model {

    protected $fillable = [
        'name',
        'salary_deduction'
    ];
    
    public function attendances(){
        return $this->hasMany('App\Models\Attendance');
    }

}
