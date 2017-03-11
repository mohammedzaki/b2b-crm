<?php

namespace App\Models;

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
        return $this->hasMany('App\Models\SupplierProcess');
    }
    
    public static function allHasOpenProcess() {
        $clients = Supplier::join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.*')->where('supplier_processes.status', 'active')
            ->get();
        return $clients;
    }

}
