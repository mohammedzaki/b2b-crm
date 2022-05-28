<?php

/*
 * B2B CRM Software License
 *
 * Copyright (C) ZakiSoft ltd - All Rights Reserved.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Mohammed Zaki mohammedzaki.dev@gmail.com, September 2017
 */

namespace App\Http\Controllers\CashManagement\Journal;

use App\Http\Controllers\Controller;
use App\Models\BankProfile;
use App\Models\DepositWithdraw;
use App\Models\FinancialCustody;
use Illuminate\Http\Response;

/**
 * Description of DailyCashController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="/cash-flow")
 * @Middleware({"web", "auth", "ability:admin,cash-flow"})
 */
class CashFlowController extends
    Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @Get("/", as="cash-flow.index")
     */
    public function index()
    {
        return $this->getItems();
    }

    private function getItems($startDate = '2000-01-01 00:00:00', $endDate = '2099-01-01 00:00:00')
    {
        $totalDW   = DepositWithdraw::calculateCurrentAmount($endDate);
        $totalFC   = FinancialCustody::totalRemainingDeposits();
        $cashItems = collect([
                                 [
                                     'name'                     => 'خزينة الشركة',
                                     'currentAmount'            => $totalDW,
                                     'postdatedDepositCheques'  => 0,
                                     'postdatedWithdrawCheques' => 0,
                                     'cashBalance'              => $totalDW
                                 ],
                                 [
                                     'name'                     => 'إجمالي العهد المتبقية',
                                     'currentAmount'            => $totalFC,
                                     'postdatedDepositCheques'  => 0,
                                     'postdatedWithdrawCheques' => 0,
                                     'cashBalance'              => $totalFC
                                 ]
                             ]);

        $banks = BankProfile::all()->mapWithKeys(function (BankProfile $bank) {
            return [
                [
                    'name'                     => $bank->name,
                    'currentAmount'            => $bank->currentAmount(),
                    'postdatedDepositCheques'  => $bank->totalPostdatedDepositCheques(),
                    'postdatedWithdrawCheques' => $bank->totalPostdatedWithdrawCheques(),
                    'cashBalance'              => $bank->cashBalance()
                ]
            ];
        });

        $cashItems = $cashItems->merge($banks);

        return view('cash.cash-flow')->with([
                                                'cashItems'                   => $cashItems,
                                                'sumDeposit'                  => $cashItems->sum('deposit'),
                                                'sumDepositCheques'           => $cashItems->sum('depositCheques'),
                                                'sumWithdraw'                 => $cashItems->sum('withdraw'),
                                                'sumWithdrawCheques'          => $cashItems->sum('withdrawCheques'),
                                                'sumCurrentAmount'            => $cashItems->sum('currentAmount'),
                                                'sumPostdatedDepositCheques'  => $cashItems->sum('postdatedDepositCheques'),
                                                'sumPostdatedWithdrawCheques' => $cashItems->sum('postdatedWithdrawCheques'),
                                                'sumCashBalance'              => $cashItems->sum('cashBalance')
                                            ]);
    }

}
