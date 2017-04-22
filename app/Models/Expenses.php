<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;

class Expenses extends Model
{
    //
    protected $fillable = [
        'name'
    ];
    
    public $startDate;
    public $endDate;
    
    public function getTotalPaid() {
        return $this->paidItems()->sum('withdrawValue');
    }
    
    public function paidItems() {
        if (!isset($this->startDate)) {
            $this->startDate = DateTime::parse('1\1\2017');
        } 
        if (!isset($this->endDate)) {
            $this->endDate = DateTime::parse('31\12\2017');
        }
        return $this->hasMany(DepositWithdraw::class, 'expenses_id')->where([
                    ['employee_id', '=', null],
                    ['due_date', '>=', $this->startDate],
                    ['due_date', '<=', $this->endDate]
        ]);
    }
}
