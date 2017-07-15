<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;

class Facility extends Model {

    protected $fillable = [
        'name',
        'manager_id',
        'type',
    ];

    public function getTaxesRate($currentDate = NULL) {
        if (is_null($currentDate)) {
            return $this->sales_tax;
        }
        $facilityTaxes = FacilityTaxes::where([
                    ['changedate', '<=', $currentDate],
                    ['enddate', '>=', $currentDate],
                ])->first();
        if (empty($facilityTaxes)) {
            return $this->sales_tax;
        } else {
            return $facilityTaxes->percentage;
        }
    }

}
