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
    const USED_PAID = 6;
    const POSTPONED = 7;
    const GUARANTEE = 8;

    const BANK_DEPOSIT    = 31;
    const BANK_WITHDRAW   = 32;
    const ATM_DEPOSIT     = 33;
    const ATM_WITHDRAW    = 34;
    const ONLINE_TRANSFER = 35;

    public static function all()
    {
        $chequeStatuses                                  = [];
        $chequeStatuses[ChequeStatuses::POSTDATED]       = "أجل";
        $chequeStatuses[ChequeStatuses::CANCELED]        = "ملغي";
        $chequeStatuses[ChequeStatuses::NOT_USED]        = "غير مستخدم";
        $chequeStatuses[ChequeStatuses::REJECTED]        = "مرفوض";
        $chequeStatuses[ChequeStatuses::STOPPED]         = "متوقف";
        $chequeStatuses[ChequeStatuses::USED_PAID]       = "مستخدم (تم الصرف)";
        $chequeStatuses[ChequeStatuses::POSTPONED]       = "تأجيل الشيك للسداد";
        $chequeStatuses[ChequeStatuses::GUARANTEE]       = "خطاب ضمان";
        $chequeStatuses[ChequeStatuses::BANK_DEPOSIT]    = "ايداع شباك";
        $chequeStatuses[ChequeStatuses::BANK_WITHDRAW]   = "سحب شباك";
        $chequeStatuses[ChequeStatuses::ATM_DEPOSIT]     = "ايداع ATM";
        $chequeStatuses[ChequeStatuses::ATM_WITHDRAW]    = "سحب ATM";
        $chequeStatuses[ChequeStatuses::ONLINE_TRANSFER] = "تحويل عبر الانترنت";
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
        $chequeStatuses[ChequeStatuses::USED_PAID] = "مستخدم (تم الصرف)";
        $chequeStatuses[ChequeStatuses::POSTPONED] = "تأجيل الشيك للسداد";
        $chequeStatuses[ChequeStatuses::GUARANTEE] = "خطاب ضمان";
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
        $chequeStatuses[ChequeStatuses::USED_PAID] = "مستخدم (تم التحصيل)";
        $chequeStatuses[ChequeStatuses::POSTPONED] = "تأجيل الشيك للسداد";
        $chequeStatuses[ChequeStatuses::GUARANTEE] = "خطاب ضمان";
        return $chequeStatuses;
    }

}