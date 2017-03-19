<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpeningAmount extends Model {

    protected $fillable = [
        'amount', 'deposit_date', 'reason', 'facility_id',
    ];
    
}
