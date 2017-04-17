<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {

    use SoftDeletes;

    /**
     * Generated
     */
    protected $dates = ['deleted_at'];
    protected $table = 'invoice';
    protected $fillable = ['id', 'invoice_number', 'total_price', 'client_id', 'invoice_date', 'deleted_at'];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
    
    public static function newInvoiceNumber () {
        $invoice_number = static::max('invoice_number');
        return sprintf("%08d", ++$invoice_number);
    }

}
