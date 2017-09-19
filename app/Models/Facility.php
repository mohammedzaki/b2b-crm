<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;

/**
 * App\Models\Facility
 *
 * @property int $id
 * @property string $name
 * @property int $manager_id
 * @property string $type
 * @property string|null $tax_file
 * @property string|null $tax_card
 * @property string|null $trade_record
 * @property int|null $sales_tax
 * @property float|null $opening_amount
 * @property int|null $country_sales_tax
 * @property string|null $logo
 * @property string|null $country
 * @property string|null $city
 * @property string|null $region
 * @property string|null $address
 * @property string|null $email
 * @property string|null $website
 * @property string|null $start_invoice_number
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereCountrySalesTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereOpeningAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereSalesTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereStartInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereTaxCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereTaxFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereTradeRecord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Facility whereWebsite($value)
 * @mixin \Eloquent
 */
class Facility extends Model {

    protected $fillable = [
        'name',
        'manager_id',
        'type',
    ];

    public function getTaxesRate($currentDate = NULL) {
        if (is_null($currentDate)) {
            return $this->sales_tax;
        }
        $facilityTaxes = FacilityTaxes::where([
                    ['changedate', '<=', $currentDate],
                    ['enddate', '>=', $currentDate],
                ])->first();
        if (empty($facilityTaxes)) {
            return $this->sales_tax;
        } else {
            return $facilityTaxes->percentage;
        }
    }

}
