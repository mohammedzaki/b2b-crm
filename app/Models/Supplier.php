<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Supplier extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'address',
        'telephone',
        'mobile',
        'debit_limit'
    ];

    public function processes() {
        return $this->hasMany(SupplierProcess::class);
    }

    public function closedProcess() {
        return $this->hasMany(SupplierProcess::class)->where('status', SupplierProcess::statusClosed);
    }

    public function openProcess() {
        return $this->hasMany(SupplierProcess::class)->where('status', SupplierProcess::statusOpened);
    }

    public static function allHasOpenProcess() {
        $suppliers = Supplier::join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
                ->select('suppliers.*')->where('supplier_processes.status', SupplierProcess::statusOpened)
                ->get();
        return $suppliers;
    }
    
    public function getTotalPaid() {
        /*return DB::table('suppliers')
                ->join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
                ->join('deposit_withdraws', 'supplier_processes.id', '=', 'deposit_withdraws.cbo_processes')
                ->where('suppliers.id', 'deposit_withdraws.supplier_id')
                ->where('supplier_processes.status', ClientProcess::statusOpened)
                ->where('suppliers.id', $this->id)
                ->sum('withdrawValue');*/
        return DB::select('SELECT 
          sum(deposit_withdraws.withdrawValue) as withdrawValue
          FROM suppliers
          join supplier_processes on supplier_processes.supplier_id = suppliers.id
          join deposit_withdraws on supplier_processes.id = deposit_withdraws.cbo_processes and suppliers.id = deposit_withdraws.supplier_id
          WHERE `status` = "active" and suppliers.id = ' . $this->id)[0]->withdrawValue;
        //return $this->hasMany(DepositWithdraw::class)->where([['withdrawValue', '>', 0]])->sum('withdrawValue');
    }
    
    public function getTotalRemaining() {
        return $this->getTotalDeal() - $this->getTotalPaid();
    }
    
    public function getTotalDeal() {
        return $this->openProcess()->sum('total_price_taxes');
    }
}
