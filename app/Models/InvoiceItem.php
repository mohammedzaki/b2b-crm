<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\InvoiceItem
 *
 * @property int $id
 * @property int $invoice_id
 * @property float $total_price
 * @property string $description
 * @property float $quantity
 * @property float $unit_price
 * @property string|null $size
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Invoice $invoice
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\InvoiceItem onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\InvoiceItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\InvoiceItem withoutTrashed()
 * @mixin \Eloquent
 */
class InvoiceItem extends Model {

    use SoftDeletes;

    /**
     * Generated
     */
    protected $dates = ['deleted_at'];
    protected $table = 'invoice_item';
    protected $fillable = ['id', 'invoice_id', 'total_price', 'description', 'quantity', 'unit_price', 'size', 'deleted_at'];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

}
