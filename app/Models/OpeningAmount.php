<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OpeningAmount
 *
 * @property int $id
 * @property float|null $amount
 * @property string|null $deposit_date
 * @property string|null $reason
 * @property int|null $facility_id
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereDepositDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpeningAmount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OpeningAmount extends Model {

    protected $fillable = [
        'amount', 'deposit_date', 'reason', 'facility_id',
    ];
    
}
