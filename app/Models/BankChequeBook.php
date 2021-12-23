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
        return 0;
    }

    public function getNewChequeNumber()
    {
        return 0;
    }
}