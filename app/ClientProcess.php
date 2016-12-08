<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProcess extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'client_id',
        'employee_id',
        'notes',
        'has_discount',
        'discount_percentage',
        'discount_reason',
        'require_bill',
        'total_price'
    ];

    public function client() {
        return $this->belongsTo('App\Client');
    }

    public function items() {
        return $this->hasMany('App\ClientProcessItem', 'process_id');
    }

    public function employee() {
        return $this->hasOne('App\Employee', 'user_id');
    }
    
    public function deposits() {
        return $this->hasMany('App\DepositWithdraw', 'cbo_processes');
    }
}
