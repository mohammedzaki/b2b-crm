<?php

namespace App;

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
        'notes'
    ];


    public function items() {
        return $this->hasMany('App\ClientProcessItem', 'process_id');
    }

    public function employee() {
        return $this->hasOne('App\Employee', 'id');
    }
    
    public function client() {
        return $this->hasOne('App\Client', 'id');
    }
    
    public function supplier() {
        return $this->hasOne('App\Supplier', 'id');
    }

    public function expenses() {
        return $this->hasOne('App\expenses', 'id');
    }
}
