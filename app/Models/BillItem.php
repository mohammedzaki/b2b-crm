<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model {

    /**
     * Generated
     */

    protected $table = 'bill_items';
    protected $fillable = ['id', 'bill_id', 'description', 'quantity', 'unit_price', 'deleted_at'];



}
