<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'manager_id',
        'type',
    ];
    
    public function getTaxesRate() {
        //$facility = Facility::findOrFail(1);
        return $this->sales_tax / 100;
    }
}
