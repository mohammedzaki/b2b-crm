<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SupplierProcess extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
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
        return $this->hasOne('App\Employee', 'id');
    }
    
    public function withdrawals() {
        return $this->hasMany('App\DepositWithdraw', 'cbo_processes');
    }
    
    public function totalWithdrawals() {
        return $this->withdrawals()->where('supplier_id', $this->supplier->id)->sum('withdrawValue');
    }

    public function totalPriceAfterTaxes() {
        return ($this->total_price - $this->discountValue()) + $this->taxesValue();
    }
    
    public function CheckProcessMustClosed() {
        if ($this->totalPriceAfterTaxes() == $this->totalWithdrawals()) {
            $this->status = 'closed';
            $this->save();
            return TRUE;
        } else {
            $this->status = 'active';
            $this->save();
            return FALSE;
        }
    }

    public function discountValue() {
        if ($this->has_discount == "1") {
            return $this->total_price * ($this->discount_percentage / 100);
        }
        return 0;
    }
    
    public function taxesValue() {
        if ($this->require_bill == "1") {
            $facility = Facility::findOrFail(1);
            return ($this->total_price - $this->discountValue()) * $facility->getTaxesRate();
        }
        return 0;
    }

}
