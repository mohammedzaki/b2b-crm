<?php
/**
 * Created by PhpStorm.
 * User: mohamedzaki
 * Date: 23/12/2021
 * Time: 2:59 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankChequeBook extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable
        = [
            'name',
            'start_number',
            'end_number',
            'bank_profile_id'
        ];

    public function totalNumbers()
    {
        return 0;
    }

    public function totalUsedNumbers()
    {
        return BankCashItem::where('cheque_book_id', $this->id)->count();
    }

    public function getNewChequeNumber()
    {
        return 0;
    }

    public function getBankCashItemsItems()
    {
        $bankCashItemsItems = collect(BankCashItem::where('cheque_book_id', $this->id)->get());
        $allBankCashItemsItems = collect();
        for ($i = $this->start_number; $i <= $this->end_number; $i++)
        {
            $item = $bankCashItemsItems->where('cheque_number', $i)->first();
            if(empty($item))
                $allBankCashItemsItems->push(new BankCashItem(['cheque_book_id' => $this->id, 'cheque_number' => $i, 'bank_profile_id' => $this->bank_profile_id]));
            else
                $allBankCashItemsItems->push($item);
        }
        return $allBankCashItemsItems;
    }

    public function bankProfile() {
        return $this->belongsTo(BankProfile::class)->withTrashed();
    }
}