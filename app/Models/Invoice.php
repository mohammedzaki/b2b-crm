<?php

namespace App\Models;

use App\Extensions\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property string $invoice_number
 * @property float $total_price
 * @property float $invoice_price
 * @property float $discount_price
 * @property float $taxes_price
 * @property float $source_discount_value
 * @property float $total_paid
 * @property float $total_remaining
 * @property string $invoice_date
 * @property string|null $invoice_due_date
 * @property int $client_id
 * @property int $status
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \App\Models\Client $client
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\InvoiceItem[] $items
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcess[] $processes
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Invoice onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereInvoiceDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereInvoicePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereSourceDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereTaxesPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereTotalPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereTotalRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Invoice withoutTrashed()
 * @mixin \Eloquent
 */
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
            $process->checkProcessMustClosed();
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
