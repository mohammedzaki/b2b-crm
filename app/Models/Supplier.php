<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Supplier
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string|null $telephone
 * @property string $mobile
 * @property string $debit_limit
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierProcess[] $closedProcess
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierProcess[] $openProcess
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierProcess[] $processes
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Supplier onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereDebitLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Supplier withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Supplier withoutTrashed()
 * @mixin \Eloquent
 */
class Supplier extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable
                     = [
            'name',
            'address',
            'telephone',
            'mobile',
            'debit_limit'
        ];

    /**
     * @return Supplier[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function allHasOpenProcess()
    {
        $suppliers = static::join('supplier_processes', 'supplier_processes.supplier_id', '=', 'suppliers.id')
                             ->select('suppliers.*')->where('supplier_processes.status', SupplierProcess::statusOpened)
                             ->distinct()
                             ->get();
        return $suppliers;
    }

    /**
     * @return Supplier[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function allAsList()
    {
        return static::all()->mapWithKeys(function ($supplier) {
            return [
                $supplier->id => [
                    'name'           => $supplier->name,
                    'hasOpenProcess' => $supplier->hasOpenProcess(),
                    'processes'      => $supplier->processes->mapWithKeys(function ($process) {
                        return [
                            $process->id => [
                                'name'   => $process->name,
                                'status' => $process->status
                            ]
                        ];
                    })
                ]
            ];
        });
    }

    public function closedProcess()
    {
        return $this->hasMany(SupplierProcess::class)->where('status', SupplierProcess::statusClosed);
    }

    public function openProcess()
    {
        return $this->hasMany(SupplierProcess::class)->where('status', SupplierProcess::statusOpened);
    }

    public function hasOpenProcess()
    {
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

    public function hasClosedProcess()
    {
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

    public function getTotalRemaining()
    {
        return $this->getTotalDeal() - $this->getTotalPaid();
    }

    public function getTotalDeal()
    {
        return $this->processes()->sum('total_price_taxes');
    }

    public function processes()
    {
        return $this->hasMany(SupplierProcess::class);
    }

    public function getTotalPaid()
    {
        $total = 0;
        foreach ($this->processes as $process) {
            $total += $process->totalWithdrawals();
        }
        return $total;
    }
}
