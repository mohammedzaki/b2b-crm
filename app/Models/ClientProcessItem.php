<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ClientProcessItem
 *
 * @property int $id
 * @property int $process_id
 * @property string $description
 * @property int $quantity
 * @property float $unit_price
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ClientProcess $process
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ClientProcessItem onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientProcessItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ClientProcessItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ClientProcessItem withoutTrashed()
 * @mixin \Eloquent
 */
class ClientProcessItem extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'process_id',
        'description',
        'quantity',
        'unit_price'
    ];

    public function process() {
        return $this->belongsTo('App\Models\ClientProcess');
    }

}
