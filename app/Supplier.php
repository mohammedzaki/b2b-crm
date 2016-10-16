<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    protected $fillable = [
        'name', 
        'address', 
        'telephone', 
        'mobile', 
        'debit_limit'
    ];
}
