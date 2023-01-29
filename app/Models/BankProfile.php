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

    /**
     * @param bool $addSelectAllOpt
     * @return BankProfile[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function allAsList($addSelectAllOpt = true, $addLocalSave = false, $addPrefix = "")
    {
        $res = static::all('id', 'name');
        $all = [];
        if($addSelectAllOpt) {
            $all['all'] = 'الكل';
        }
        if($addLocalSave) {
            $all['0'] = $addPrefix . " " . 'خزنة الشركة';
        }
        foreach ($res as $bank) {
            $all[$bank->id] = $addPrefix . " " . $bank->name;
        }
        return $all;
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

    public function cashDeposits()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::BANK_DEPOSIT, ChequeStatuses::ATM_DEPOSIT])
                    ->whereNotNull('depositValue');
    }

    public function chequeDeposits()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::USED_PAID])
                    ->whereNotNull('depositValue');
    }

    public function deposits()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::USED_PAID, ChequeStatuses::BANK_DEPOSIT, ChequeStatuses::ATM_DEPOSIT])
                    ->whereNotNull('depositValue');
    }

    public function postdatedDepositCheques()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED])
                    ->whereNotNull('depositValue');
    }

    public function cashWithdraws()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::BANK_WITHDRAW, ChequeStatuses::ATM_WITHDRAW])
                    ->whereNotNull('withdrawValue');
    }

    public function chequeWithdraws()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::USED_PAID])
                    ->whereNotNull('withdrawValue');
    }

    public function withdraws()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::USED_PAID, ChequeStatuses::BANK_WITHDRAW, ChequeStatuses::ATM_WITHDRAW])
                    ->whereNotNull('withdrawValue');
    }

    public function postdatedWithdrawCheques()
    {
        return $this->cashItems()
                    ->whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED])
                    ->whereNotNull('withdrawValue');
    }

    public function totalCashDeposits()
    {
        return $this->cashDeposits()->sum('depositValue');
    }

    public function totalChequeDeposits()
    {
        return $this->chequeDeposits()->sum('depositValue');
    }

    public function totalDeposits()
    {
        return $this->deposits()->sum('depositValue');
    }

    public function totalPostdatedDepositCheques()
    {
        return $this->postdatedDepositCheques()->sum('depositValue');
    }

    public function totalCashWithdraws()
    {
        return $this->cashWithdraws()->sum('withdrawValue');
    }

    public function totalChequeWithdraws()
    {
        return $this->chequeWithdraws()->sum('withdrawValue');
    }

    public function totalWithdraws()
    {
        return $this->withdraws()->sum('withdrawValue');
    }

    public function totalPostdatedWithdrawCheques()
    {
        return $this->postdatedWithdrawCheques()->sum('withdrawValue');
    }

    public function currentAmount()
    {
        $loanAmounts = Loans::where('save_id', '=', $this->id)->sum('amount');
        $openingAmounts = OpeningAmount::where('save_id', '=', $this->id)->sum('amount');
        return ($this->totalDeposits() + $loanAmounts + $openingAmounts) - $this->totalWithdraws();
    }

    public function cashBalance()
    {
        return ($this->currentAmount() + $this->totalPostdatedDepositCheques()) - $this->totalPostdatedWithdrawCheques();
    }
}
