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
        'has_source_discount',
        'source_discount_percentage',
        'source_discount_value',
        'require_invoice',
        'total_price',
        'total_price_taxes',
        'taxes_value'
    ];
    public $client_id = 0;
    const statusClosed = 'closed';
    const statusOpened = 'active';

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function items() {
        return $this->hasMany(SupplierProcessItem::class, 'process_id');
    }

    public function employee() {
        return $this->hasOne(Employee::class, 'id');
    }

    public function clientProcess() {
        return $this->belongsTo(ClientProcess::class);
    }
    
    public function withdrawals() {
        return $this->hasMany(DepositWithdraw::class, 'cbo_processes')->where([
            ['supplier_id', "=",  $this->supplier->id],
            ['withdrawValue', ">", 0],
        ]);
    }

    public function totalWithdrawals() {
        return $this->withdrawals()->sum('withdrawValue');
    }

    public function totalPriceAfterTaxes() {
        return ($this->total_price - $this->discount_value) + $this->taxesValue();
    }

    public function CheckProcessMustClosed() {
        if ($this->totalPriceAfterTaxes() == $this->totalWithdrawals()) {
            $this->status = static::statusClosed;
            $this->save();
            return TRUE;
        } else {
            $this->status = static::statusOpened;
            $this->save();
            return FALSE;
        }
    }

    public function taxesValue() {
        if ($this->require_invoice == TRUE) {
            return $this->taxes_value;
        }
        return 0;
    }

    public static function allOpened() {
        return SupplierProcess::where('status', static::statusOpened);
    }

}
