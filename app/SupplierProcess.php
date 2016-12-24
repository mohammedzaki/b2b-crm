<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SupplierProcess extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'client_process_id',
        'supplier_id',
        'employee_id',
        'notes',
        'has_discount',
        'discount_percentage',
        'discount_reason',
        'require_bill',
        'total_price'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function items()
    {
        return $this->hasMany('App\SupplierProcessItem', 'process_id');
    }

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id');
    }

}
