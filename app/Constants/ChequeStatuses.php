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

    const POSTDATED = 1;
    const CANCELED  = 2;
    const NOT_USED  = 3;
    const REJECTED  = 4;
    const STOPPED   = 5;
    const USED      = 6; // Paid
    const POSTPONED = 7;

    public static function all()
    {
        $chequeStatuses                            = [];
        $chequeStatuses[ChequeStatuses::POSTDATED] = "أجل";
        $chequeStatuses[ChequeStatuses::CANCELED]  = "ملغي";
        $chequeStatuses[ChequeStatuses::NOT_USED]  = "غير مستخدم";
        $chequeStatuses[ChequeStatuses::REJECTED]  = "مرفوض";
        $chequeStatuses[ChequeStatuses::STOPPED]   = "متوقف";
        $chequeStatuses[ChequeStatuses::USED]      = "مستخدم";
        $chequeStatuses[ChequeStatuses::POSTPONED] = "تأجيل الشيك للسداد";
        return $chequeStatuses;
    }

    /**
     * @return array
     */
    public static function withdrawStatuses()
    {
        $chequeStatuses                            = [];
        $chequeStatuses[ChequeStatuses::POSTDATED] = "أجل";
        $chequeStatuses[ChequeStatuses::CANCELED]  = "ملغي";
        $chequeStatuses[ChequeStatuses::NOT_USED]  = "غير مستخدم";
        $chequeStatuses[ChequeStatuses::REJECTED]  = "مرفوض";
        $chequeStatuses[ChequeStatuses::STOPPED]   = "متوقف";
        $chequeStatuses[ChequeStatuses::USED]      = "تم الصرف";
        $chequeStatuses[ChequeStatuses::POSTPONED] = "تأجيل الشيك للسداد";
        return $chequeStatuses;
    }

    /**
     * @return array
     */
    public static function depositStatuses()
    {
        $chequeStatuses                            = [];
        $chequeStatuses[ChequeStatuses::POSTDATED] = "أجل";
        $chequeStatuses[ChequeStatuses::REJECTED]  = "مرفوض";
        $chequeStatuses[ChequeStatuses::USED]      = "تم التحصيل";
        $chequeStatuses[ChequeStatuses::POSTPONED] = "تأجيل الشيك للسداد";
        return $chequeStatuses;
    }

}