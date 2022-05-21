<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\ChequeStatuses;

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

    public function getCurrentChequeBook()
    {
        return $this->chequeBooks()->first();
    }

    public function chequeBooks()
    {
        return $this->hasMany(BankChequeBook::class, 'bank_profile_id');
    }

    public function deposits()
    {
        return $this->cashItems()->whereIn('cheque_status', [ChequeStatuses::BANK_DEPOSIT, ChequeStatuses::ATM_DEPOSIT]);
    }

    public function depositCheques()
    {
        return $this->cashItems()->whereIn('cheque_status', [ChequeStatuses::USED_PAID]);
    }

    public function withdraws()
    {
        return $this->cashItems()->whereIn('cheque_status', [ChequeStatuses::BANK_WITHDRAW, ChequeStatuses::ATM_WITHDRAW]);
    }

    public function withdrawCheques()
    {
        return $this->cashItems()->whereIn('cheque_status', [ChequeStatuses::USED_PAID]);
    }

    public function postdatedDepositCheques()
    {
        return $this->cashItems()->whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED]);
    }

    public function postdatedWithdrawCheques()
    {
        return $this->cashItems()->whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED]);
    }

    public function totalDeposits()
    {
        return $this->deposits()->sum('depositValue');
    }

    public function totalDepositCheques()
    {
        return $this->depositCheques()->sum('depositValue');
    }

    public function totalWithdraws()
    {
        return $this->withdraws()->sum('withdrawValue');
    }

    public function totalWithdrawCheques()
    {
        return $this->withdrawCheques()->sum('withdrawValue');
    }

    public function totalPostdatedDepositCheques()
    {
        return $this->postdatedDepositCheques()->sum('depositValue');
    }

    public function totalPostdatedWithdrawCheques()
    {
        return $this->postdatedWithdrawCheques()->sum('withdrawValue');
    }

    public function currentAmount()
    {
        return ($this->totalDeposits() + $this->totalDepositCheques()) - ($this->totalWithdraws() + $this->totalWithdrawCheques());
    }

    public function cashBalance()
    {
        return 0;
    }
}
