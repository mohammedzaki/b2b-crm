<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class BankProfile extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable
        = [
            'name',
            'statement_number',
            'branch_address'
        ];

    public static function allAsList()
    {
        return static::all('id', 'name')->mapWithKeys(function ($bank) {
            return [$bank->id => $bank->name];
        });
    }

    public function cashItems()
    {
        return $this->hasMany(BankCashItem::class, 'bank_profile_id');
    }

    public function chequeBooks()
    {
        return $this->hasMany(BankChequeBook::class, 'bank_profile_id');
    }

    public function getCurrentChequeBook()
    {
        return $this->chequeBooks()->first();
    }
}
