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
    protected $table = 'invoice_item';
    protected $fillable = ['id', 'invoice_id', 'total_price', 'description', 'quantity', 'unit_price', 'size', 'deleted_at'];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

}
