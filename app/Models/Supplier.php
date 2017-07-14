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
                ->distinct()
                ->get();
        return $suppliers;
    }

    public function hasOpenProcess() {
        $supplier = Supplier::join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
                ->select('suppliers.*')->where([
                    ['supplier_processes.status', "=", SupplierProcess::statusOpened],
                    ['suppliers.id', '=', $this->id]])
                ->distinct()
                ->get();
        
        if ($supplier->count() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function hasClosedProcess() {
        $supplier = Supplier::join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
                ->select('suppliers.*')->where([
                    ['supplier_processes.status', "=", SupplierProcess::statusClosed],
                    ['suppliers.id', '=', $this->id]])
                ->distinct()
                ->get();
        
        if ($supplier->count() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function getTotalPaid() {
        return DB::table('suppliers')
                ->join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
                ->join('deposit_withdraws', 'supplier_processes.id', '=', 'deposit_withdraws.cbo_processes')
                ->join('deposit_withdraws as dw', 'suppliers.id', '=', 'deposit_withdraws.supplier_id')
                ->distinct()
                ->where('supplier_processes.status', SupplierProcess::statusOpened)
                ->where('suppliers.id', $this->id)
                ->sum('deposit_withdraws.withdrawValue');
    }
    
    public function getTotalRemaining() {
        return $this->getTotalDeal() - $this->getTotalPaid();
    }
    
    public function getTotalDeal() {
        return $this->openProcess()->sum('total_price_taxes');
    }
}
