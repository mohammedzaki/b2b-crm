<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpeningAmount extends Model {

    protected $fillable = [
        'amount', 'date', 'reason', 'facility_id',
    ];
    
}
