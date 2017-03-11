<?php

namespace App\Models;

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
        'status',
        'discount_percentage',
        'discount_value',
        'discount_reason',
        'require_invoice',
        'invoice_billed',
        'total_price',
        'total_price_taxes',
        'taxes_value',
        'invoice_id'
    ];

    const invoiceUnBilled = 0;
    const invoiceBilled = 1;

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }

    public function items() {
        return $this->hasMany('App\Models\ClientProcessItem', 'process_id');
    }
    
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function employee() {
        return $this->hasOne('App\Models\Employee', 'id');
    }

    private function clientProcessDeposits() {
        return $this->hasMany('App\Models\DepositWithdraw', 'cbo_processes');
    }

    public function deposits() {
        return $this->clientProcessDeposits()->where('client_id', $this->client->id)->get();
    }

    public function totalDeposits() {
        return $this->clientProcessDeposits()->where('client_id', $this->client->id)->sum('depositValue');
    }

    public function totalPriceAfterTaxes() {
        return ($this->total_price - $this->discountValue()) + $this->taxesValue();
    }

    public function CheckProcessMustClosed() {
        if ($this->totalPriceAfterTaxes() == $this->totalDeposits()) {
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
            return $this->discount_value;
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
            return $this->taxes_value;
        }
        return 0;
    }

    public static function allOpened() {
        return ClientProcess::where('status', 'active');
    }

}
