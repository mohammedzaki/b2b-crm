<?php

namespace App\Models;

use App\Extensions\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {

    use SoftDeletes;

    /**
     * Generated
     */
    protected $dates = ['deleted_at'];
    protected $table = 'invoice';
    protected $fillable = ['id', 'invoice_number', 'invoice_price',
        'discount_price',
        'taxes_price',
        'source_discount_value',
        'total_price',
        'total_paid',
        'total_remaining', 'client_id', 'invoice_date', 'invoice_due_date', 'status', 'deleted_at'];

    const UN_PAID = 0;
    const PAID = 1;

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function processes() {
        return $this->hasMany(ClientProcess::class, 'invoice_id');
    }

    public function totalPaid() {
        $totalPaid = 0;
        foreach ($this->processes as $process) {
            $totalPaid += $process->totalDeposits();
        }
        return $totalPaid;
    }

    public function totalRemaining() {
        $totalRemaining = 0;
        foreach ($this->processes as $process) {
            $totalRemaining += $process->totalRemaining();
        }
        return $totalRemaining;
    }

    public static function newInvoiceNumber() {
        $start_invoice_number = Facility::max('start_invoice_number');
        $invoice_number = static::max('invoice_number') + 1;
        if ($start_invoice_number > $invoice_number) {
            $invoice_number = $start_invoice_number;
        }
        return sprintf("%08d", $invoice_number);
    }

    public static function getLastInvoiceDate() {
        $invoice = Invoice::find(static::max('id') - 1);
        if (!empty($invoice)) {
            return $invoice->invoice_date;
        } else {
            return DateTime::parse('01-01-1970');
        }
    }

    public function pay() {
        $this->status = static::PAID;
        $this->save();
        foreach ($this->processes as $process) {
            $process->payRemaining($this->invoice_number);
            $process->CheckProcessMustClosed();
        }
    }

    public function getPrevInvoiceDate() {
        $invoice = Invoice::find($this->id - 1);
        if (!empty($invoice)) {
            return $invoice->invoice_date;
        } else {
            return DateTime::parse('01-01-1970');
        }
    }

    public function getNextInvoiceDate() {
        $invoice = Invoice::find($this->id + 1);
        if (!empty($invoice)) {
            return $invoice->invoice_date;
        } else {
            return DateTime::now();
        }
    }

    public function isLastInvoice() {
        return $this->invoice_number == static::max('invoice_number');
    }

}
