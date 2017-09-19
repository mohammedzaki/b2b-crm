<?php

namespace App\Models;

use App\Constants\PaymentMethods;
use App\Extensions\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\ClientProcess
 *
 * @property int $id
 * @property string $name
 * @property int $client_id
 * @property int $employee_id
 * @property string $status
 * @property string|null $notes
 * @property int|null $has_discount
 * @property float|null $discount_percentage
 * @property float|null $discount_value
 * @property string|null $discount_reason
 * @property int|null $has_source_discount
 * @property float|null $source_discount_percentage
 * @property float|null $source_discount_value
 * @property int $require_invoice
 * @property int $invoice_billed
 * @property float $taxes_value
 * @property int|null $taxes_percentage
 * @property float|null $total_price_taxes
 * @property float $total_price
 * @property int|null $invoice_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $deposits
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\Invoice|null $invoice
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcessItem[] $items
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ClientProcess onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereDiscountReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereHasDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereHasSourceDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereInvoiceBilled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereRequireInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereSourceDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereSourceDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereTaxesPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereTaxesValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereTotalPriceTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ClientProcess withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ClientProcess withoutTrashed()
 * @mixin \Eloquent
 */
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
