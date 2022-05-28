<?php

namespace App\Reports\V2\Client;

use App\Extensions\DateTime;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Reports\V2\BaseReport;

class ClientDetailed extends
    BaseReport
{

    public
        $clientId,
        $processIds;

    public function __construct($withLetterHead = true, $clientId = null, $processIds = null)
    {
        parent::__construct($withLetterHead);
        $this->processIds = $processIds;
        $this->clientId   = $clientId;
    }

    public function setReportRefs()
    {
        $this->reportName       = 'Client_Statement_Detailed_Report';
        $this->reportView       = 'reports.client.account-statement.detailed';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.client.accountStatement.printPDF';
    }

    /**
     * @param bool $withUserLog
     * @return array
     */
    public function getReportData($withUserLog = false)
    {
        $client                   = Client::findOrFail($this->clientId);
        $clientName               = $client->name;
        $allProcessesTotalPrice   = 0;
        $allProcessTotalPaid      = 0;
        $allProcessTotalRemaining = 0;
        $date                     = DateTime::todayDateformat();
        $processes                = [];

        foreach ($this->processIds as $id) {
            $clientProcess = ClientProcess::findOrFail($id);
            if ($clientProcess instanceof ClientProcess);
            $processes[$id]['processName']           = $clientProcess->name;
            $processes[$id]['processTotalPrice']     = ($clientProcess->total_price + $clientProcess->taxesValue()) - $clientProcess->source_discount_value;
            $processes[$id]['processTotalPaid']      = $clientProcess->totalDeposits() + $clientProcess->discount_value;
            $processes[$id]['processTotalRemaining'] = $clientProcess->total_price_taxes - $clientProcess->totalDeposits();
            $processes[$id]['processDate']           = DateTime::todayDateformat(); //Print Date
            $processes[$id]['processNum']            = $id;

            $allProcessesTotalPrice   += $processes[$id]['processTotalPrice'];
            $allProcessTotalPaid      += $processes[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $processes[$id]['processTotalRemaining'];

            $index             = 0;
            $totalDepositValue = 0;
            foreach ($clientProcess->items as $item) {
                $processes[$id]['processDetails'][$index]['pending']    = "";
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parseToDateFormat($item->created_at);
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                $processes[$id]['processDetails'][$index]['unitPrice']  = $item->unit_price;
                $processes[$id]['processDetails'][$index]['quantity']   = $item->quantity;
                $processes[$id]['processDetails'][$index]['desc']       = $item->description;
                if ($withUserLog) {
                    $processes[$id]['processDetails'][$index]['id'] = $item->id;
                }
                $index++;
            }
            if ($clientProcess->has_discount == TRUE) {
                $processes[$id]['processDetails'][$index]['pending']    = "";
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parseToDateFormat($clientProcess->created_at);
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = $clientProcess->discount_value;
                $processes[$id]['processDetails'][$index]['totalPrice'] = "";
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = "خصم بسبب : " . $clientProcess->discount_reason;
                $index++;
            }
            if ($clientProcess->has_source_discount == TRUE) {
                $processes[$id]['processDetails'][$index]['pending']    = "";
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parseToDateFormat($clientProcess->created_at);
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->source_discount_value;
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = "خصم من المنبع";
                $index++;
            }
            if ($clientProcess->require_invoice == TRUE) {
                $processes[$id]['processDetails'][$index]['pending']    = "";
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parseToDateFormat($clientProcess->created_at);
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->taxesValue();
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = "قيمة الضريبة المضافة";
                $index++;
            }
            $items = $withUserLog ? $clientProcess->depositsWithTrashed() : $clientProcess->deposits();
            foreach ($items as $deposit) {
                $totalDepositValue                                      += $deposit->depositValue;
                $processes[$id]['processDetails'][$index]['pending']    = $deposit->pendingStatus;
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parseToDateFormat($deposit->due_date);
                $processes[$id]['processDetails'][$index]['remaining']  = $clientProcess->total_price_taxes - $totalDepositValue;
                $processes[$id]['processDetails'][$index]['paid']       = $deposit->depositValue;
                $processes[$id]['processDetails'][$index]['totalPrice'] = "";
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = $deposit->recordDesc;
                if ($withUserLog) {
                    $processes[$id]['processDetails'][$index]['deleted'] = $deposit->deleted_at;
                    $processes[$id]['processDetails'][$index]['id']      = $deposit->id;
                }
                $index++;
            }
        }
        $data = [
            'clientName'               => $clientName,
            'date'                     => $date,
            'withUserLog'              => $withUserLog,
            'processes'                => $processes,
            'allProcessesTotalPrice'   => $allProcessesTotalPrice,
            'allProcessTotalPaid'      => $allProcessTotalPaid,
            'allProcessTotalRemaining' => $allProcessTotalRemaining
        ];
        return $data;
    }
}
