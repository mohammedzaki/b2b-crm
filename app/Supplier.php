<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 
        'address', 
        'telephone', 
        'mobile', 
        'debit_limit'
    ];

    public function processes()
    {
        return $this->hasMany('App\SupplierProcess');
    }

}
