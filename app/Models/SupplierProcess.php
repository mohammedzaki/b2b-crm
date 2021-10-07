<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

/**
 * App\Models\SupplierProcess
 *
 * @property int $id
 * @property string|null $name
 * @property int $client_process_id
 * @property int $supplier_id
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
 * @property float|null $taxes_value
 * @property int|null $taxes_percentage
 * @property float|null $total_price_taxes
 * @property float $total_price
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $created_at
 * @property-read \App\Models\ClientProcess $clientProcess
 * @property-read \App\Models\Employee $employee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierProcessItem[] $items
 * @property-read \App\Models\Supplier $supplier
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $withdrawals
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SupplierProcess onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereClientProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereDiscountReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereHasDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereHasSourceDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereRequireInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereSourceDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereSourceDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereTaxesPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereTaxesValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereTotalPriceTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SupplierProcess withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SupplierProcess withoutTrashed()
 * @mixin \Eloquent
 */
class SupplierProcess extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'client_process_id',
        'supplier_id',
        'employee_id',
        'status',
        'notes',
        'has_discount',
        'discount_percentage',
        'discount_value',
        'discount_reason',
        'has_source_discount',
        'source_discount_percentage',
        'source_discount_value',
        'require_invoice',
        'taxes_value',
        'taxes_percentage',
        'total_price_taxes',
        'total_price',
    ];
    public $client_id   = 0;

    const statusClosed = 'closed';
    const statusOpened = 'active';

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function items() {
        return $this->hasMany(SupplierProcessItem::class, 'process_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function clientProcess() {
        return $this->belongsTo(ClientProcess::class);
    }

    public function dwWithdrawals()
    {
        return $this->hasMany(DepositWithdraw::class, 'cbo_processes')->where([
                                                                                  ['supplier_id', "=", $this->supplier_id],
                                                                                  ['withdrawValue', ">", 0]
                                                                              ])->select(DB::raw('NULL as pendingStatus'), 'withdrawValue', 'due_date', 'recordDesc');
    }

    public function fcWithdrawals()
    {
        return $this->hasMany(FinancialCustodyItem::class, 'cbo_processes')->where([
                                                                                       ['supplier_id', "=", $this->supplier_id],
                                                                                       ['withdrawValue', ">", 0]
                                                                                   ])->select(DB::raw('IF(ISNULL(approved_at),1,NULL) AS pendingStatus'), 'withdrawValue', 'due_date', 'recordDesc');
    }

    public function withdrawals()
    {
        return collect($this->dwWithdrawals)->merge($this->fcWithdrawals)->sortBy('due_date');
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
