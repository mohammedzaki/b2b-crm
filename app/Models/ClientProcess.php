<?php

namespace App\Models;

use App\Constants\PaymentMethods;
use App\Extensions\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        'has_source_discount',
        'source_discount_percentage',
        'source_discount_value',
        'invoice_billed',
        'total_price',
        'total_price_taxes',
        'taxes_value',
        'taxes_percentage',
        'invoice_id'
    ];

    const invoiceUnBilled = 0;
    const invoiceBilled = 1;
    const statusClosed = 'closed';
    const statusOpened = 'active';

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

    public function deposits() {
        return $this->hasMany('App\Models\DepositWithdraw', 'cbo_processes')->where([
                    ['client_id', "=", $this->client_id],
                    ['depositValue', ">", 0],
        ]);
    }

    public function totalDeposits() {
        return $this->deposits()->sum('depositValue');
    }

    public function totalRemaining() {
        return $this->total_price_taxes - $this->totalDeposits();
    }

    public function payRemaining($invoice_no) {
        if ($this->totalRemaining() > 0) {
            $all = [
                'due_date' => DateTime::now(),
                'depositValue' => $this->totalRemaining(),
                'cbo_processes' => $this->id,
                'client_id' => $this->client_id,
                'recordDesc' => "تحصيل فاتورة رقم {$invoice_no}",
                'payMethod' => PaymentMethods::CASH,
                'notes' => ""
            ];
            DepositWithdraw::create($all);
        }
        $this->CheckProcessMustClosed();
    }

    public function CheckProcessMustClosed() {
        if ($this->totalRemaining() == 0) {
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
        return ClientProcess::where('status', static::statusOpened);
    }

}
