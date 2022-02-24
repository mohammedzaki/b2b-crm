<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\DateTime;

/**
 * App\Models\Expenses
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DepositWithdraw[] $paidItems
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Expenses extends Model
{
    //
    protected $fillable = [
        'name'
    ];
    
    public $startDate;
    public $endDate;
    
    public function getTotalPaid() {
        return $this->paidItems()->sum('withdrawValue');
    }

    public function dwItems()
    {
        if (!isset($this->startDate)) {
            $this->startDate = DateTime::parse('01-01-2017');
        }
        if (!isset($this->endDate)) {
            $this->endDate = DateTime::parse('31-12-2017');
        }
        return $this->hasMany(DepositWithdraw::class, 'expenses_id')->where([
                                                                                ['employee_id', '=', null],
                                                                                ['due_date', '>=', $this->startDate],
                                                                                ['due_date', '<=', $this->endDate]
                                                                            ]);
    }

    public function fcItems()
    {
        if (!isset($this->endDate)) {
            $this->endDate = DateTime::parse('31-12-2017');
        }
        return $this->hasMany(FinancialCustodyItem::class, 'expenses_id')->where([
                                                                                ['employee_id', '=', null],
                                                                                ['due_date', '>=', $this->startDate],
                                                                                ['due_date', '<=', $this->endDate]
                                                                            ]);
    }

    public function paidItems() {
        return collect($this->dwItems)->merge($this->fcItems)->sortBy('due_date');
    }
}
