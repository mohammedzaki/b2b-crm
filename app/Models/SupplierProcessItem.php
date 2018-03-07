<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SupplierProcessItem
 *
 * @property int $id
 * @property int $process_id
 * @property string $description
 * @property int $quantity
 * @property float $unit_price
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\SupplierProcess $process
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SupplierProcessItem onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupplierProcessItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SupplierProcessItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SupplierProcessItem withoutTrashed()
 * @mixin \Eloquent
 */
class SupplierProcessItem extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'process_id',
        'description',
        'quantity',
        'unit_price'
    ];

    public function process()
    {
        return $this->belongsTo('App\Models\SupplierProcess');
    }
}
