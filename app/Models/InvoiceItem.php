<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model {

    use SoftDeletes;

    /**
     * Generated
     */
    protected $dates = ['deleted_at'];
    protected $table = 'invoice_items';
    protected $fillable = ['id', 'invoice_id', 'description', 'quantity', 'unit_price', 'deleted_at'];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

}
