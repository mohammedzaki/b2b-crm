<?php

namespace App\Constants;

class PaymentMethods {

    const CASH = 0;
    const CHEQUE = 1;

    public static function all() {
        $payMethods = [];
        $payMethods[PaymentMethods::CASH] = "كاش";
        $payMethods[PaymentMethods::CHEQUE] = "شيك";
        return $payMethods;
    }

}
