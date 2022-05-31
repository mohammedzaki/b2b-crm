<?php

namespace App\Models;

use App\Constants\PaymentMethods;
use App\Extensions\DateTime;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\ChequeStatuses;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $depositsWithTrashed
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $expenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $manpowerCost
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierProcess[] $suppliers
 */
class ClientProcess extends
    Model
{

    use SoftDeletes;

    const invoiceUnBilled = 0;
    const invoiceBilled   = 1;
    const statusClosed    = 'closed';
    const statusOpened    = 'active';
    protected $dates = ['deleted_at'];
    protected $fillable
                     = [
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

    public static function allOpened()
    {
        return ClientProcess::where('status', static::statusOpened);
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(ClientProcessItem::class, 'process_id');
    }

    public function suppliers()
    {
        return $this->hasMany(SupplierProcess::class, 'client_process_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function bankDeposits()
    {
        return $this->hasMany(BankCashItem::class, 'cbo_processes')
                    ->where([
                                ['client_id', "=", $this->client_id],
                                ['depositValue', ">", 0],
                            ])
                    ->select(DB::raw('IF(cheque_status <> ' . ChequeStatuses::POSTDATED . ' OR cheque_status <> ' . ChequeStatuses::POSTPONED . ',1,NULL) AS pendingStatus, bank_cashes.*'));
    }

    public function dwDeposits()
    {
        return $this->hasMany(DepositWithdraw::class, 'cbo_processes')
                    ->where([
                                ['client_id', "=", $this->client_id],
                                ['depositValue', ">", 0],
                            ])
                    ->select(DB::raw('NULL AS pendingStatus, deposit_withdraws.*'));
    }

    public function dwExpenses()
    {
        return $this->hasMany(DepositWithdraw::class, 'cbo_processes')
                    ->where([
                                ['client_id', "=", $this->client_id],
                                ['withdrawValue', ">", 0],
                            ])
                    ->select(DB::raw('NULL as pendingStatus'), 'withdrawValue', 'due_date', 'recordDesc');
    }

    public function fcExpenses()
    {
        return $this->hasMany(FinancialCustodyItem::class, 'cbo_processes')
                    ->where([
                                ['client_id', "=", $this->client_id],
                                ['withdrawValue', ">", 0],
                                ['approved_at', '=', null]
                            ])
                    ->select(DB::raw('IF(ISNULL(approved_at),1,NULL) AS pendingStatus'), 'withdrawValue', 'due_date', 'recordDesc');
    }

    public function bankExpenses()
    {
        return $this->hasMany(BankCashItem::class, 'cbo_processes')
                    ->where([
                                ['client_id', "=", $this->client_id],
                                ['withdrawValue', ">", 0]
                            ])
                    ->select(DB::raw('IF(cheque_status <> ' . ChequeStatuses::POSTDATED . ' OR cheque_status <> ' . ChequeStatuses::POSTPONED . ',1,NULL) AS pendingStatus'), 'withdrawValue', 'due_date', 'recordDesc');
    }

    public function expenses()
    {
        return collect($this->dwExpenses)->merge($this->fcExpenses)->merge($this->bankExpenses)->sortBy('due_date');
    }

    public function manpowerCost()
    {
        return $this->hasMany(Attendance::class, 'process_id');
//                ->select(
//                        'employee_id',
//                        DB::raw('count(employee_id) as totalDays'),
//                        DB::raw('SUM(working_hours_in_seconds) as working_hours_in_seconds'))
//                ->groupBy('employee_id');
    }

    public function companyExpences()
    {

    }

    public function payRemaining($invoice_no)
    {
        if ($this->totalRemaining() > 0) {
            $all = [
                'due_date'      => DateTime::now(),
                'depositValue'  => $this->totalRemaining(),
                'cbo_processes' => $this->id,
                'client_id'     => $this->client_id,
                'recordDesc'    => "تحصيل فاتورة رقم {$invoice_no}",
                'payMethod'     => PaymentMethods::CASH,
                'notes'         => ""
            ];
            DepositWithdraw::create($all);
        }
        $this->checkProcessMustClosed();
    }

    public function totalRemaining()
    {
        return $this->total_price_taxes - $this->totalDeposits();
    }

    public function totalDeposits()
    {
        return $this->deposits()->sum('depositValue');
    }

    public function deposits()
    {
        return collect($this->dwDeposits)->merge($this->bankDeposits)->sortBy('due_date');
    }

    public function depositsWithTrashed()
    {
        return collect($this->dwDeposits()->withTrashed()->get())->merge($this->bankDeposits()->withTrashed()->get())->sortBy('due_date');
    }

    public function checkProcessMustClosed()
    {
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

    public function taxesValue()
    {
        if ($this->require_invoice == TRUE) {
            return $this->taxes_value;
        }
        return 0;
    }

}
