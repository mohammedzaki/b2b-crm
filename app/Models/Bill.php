<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {

    /**
     * Generated
     */

    protected $table = 'bills';
    protected $fillable = ['id', 'bill_number', 'total_price', 'client_id', 'bill_date', 'deleted_at'];



}
