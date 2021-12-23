<?php
/**
 * Created by PhpStorm.
 * User: mohamedzaki
 * Date: 23/12/2021
 * Time: 6:50 AM
 */

namespace App\Constants;


class ChequeStatuses
{

    const CANCELED = 1;
    const NOT_USED = 2;
    const REJECTED = 3;
    const STOPPED  = 4;
    const USED     = 5;

    public static function all()
    {
        $chequeStatuses                           = [];
        $chequeStatuses[ChequeStatuses::CANCELED] = "ملغي";
        $chequeStatuses[ChequeStatuses::NOT_USED] = "غير مستخدم";
        $chequeStatuses[ChequeStatuses::REJECTED] = "مرفوض";
        $chequeStatuses[ChequeStatuses::STOPPED]  = "متوقف";
        $chequeStatuses[ChequeStatuses::USED]     = "مستخدم";
        return $chequeStatuses;
    }

}