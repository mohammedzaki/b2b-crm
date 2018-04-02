<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacilityTaxes
 *
 * @property int $id
 * @property int|null $percentage
 * @property string|null $changedate
 * @property string|null $enddate
 * @property int|null $facility_id
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes whereChangedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes whereEnddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes whereFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FacilityTaxes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacilityTaxes extends Model {

    protected $fillable = [
        'percentage', 'changedate', 'enddate', 'facility_id',
    ];

    public function getLast() {
        return static::all()->get(count(static::all()) - 2);
    }
}
