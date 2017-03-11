<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierProcess extends Model {

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
        'discount_value',
        'discount_reason',
        'require_invoice',
        'total_price',
        'total_price_taxes',
        'taxes_value'
    ];
    public $client_id = 0;

    public function supplier() {
        return $this->belongsTo('App\Models\Supplier');
    }

    public function items() {
        return $this->hasMany('App\Models\SupplierProcessItem', 'process_id');
    }

    public function employee() {
        return $this->hasOne('App\Models\Employee', 'id');
    }
    
    private function supplierProcessWithdrawals() {
        return $this->hasMany('App\Models\DepositWithdraw', 'cbo_processes');
    }
    
    public function withdrawals() {
        return $this->supplierProcessWithdrawals()->where('supplier_id', $this->supplier->id)->get();
    }

    public function totalWithdrawals() {
        return $this->supplierProcessWithdrawals()->where('supplier_id', $this->supplier->id)->sum('withdrawValue');
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
            return $this->discount_value; //* ($this->discount_percentage / 100);
        }
        return 0;
    }

    public function discountPercentage() {
        if ($this->has_discount == "1") {
            return ($this->discount_value / $this->total_price) * 100;
        }
        return 0;
    }

    public function taxesValue() {
        if ($this->require_invoice == "1") {
            //$facility = Facility::findOrFail(1);
            //($this->total_price - $this->discountValue()) * $facility->getTaxesRate();
            return $this->taxes_value;
        }
        return 0;
    }

    public static function allOpened() {
        return SupplierProcess::where('status', 'active');
    }

}
