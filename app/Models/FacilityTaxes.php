<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityTaxes extends Model {

    protected $fillable = [
        'percentage', 'changedate', 'enddate', 'facility_id',
    ];

    public function getLast() {
        return static::all()->get(count(static::all()) - 2);
    }
}
