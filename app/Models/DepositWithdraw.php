<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositWithdraw extends Model {

    //use SoftDeletes;

    //protected $dates = ['deleted_at'];
    protected $fillable = [
        'depositValue',
        'withdrawValue',
        'recordDesc',
        
        'cbo_processes',
        'client_id',
        'employee_id',
        'supplier_id',
        'expenses_id',
        
        'payMethod',
        'notes',
        'due_date'
    ];


    public function items() {
        return $this->hasMany('App\Models\ClientProcessItem', 'process_id');
    }

    public function employee() {
        return $this->hasOne('App\Models\Employee', 'id');
    }
    
    public function client() {
        return $this->hasOne('App\Models\Client', 'id');
    }
    
    public function supplier() {
        return $this->hasOne('App\Models\Supplier', 'id');
    }

    public function expenses() {
        return $this->hasOne('App\Models\Expenses', 'id');
    }
}
