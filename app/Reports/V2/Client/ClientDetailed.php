<?php

namespace App\Reports\V2\Client;

use App\Models\Client;
use App\Models\ClientProcess;
use App\Reports\V2\BaseReport;
use App\Extensions\DateTime;


class ClientDetailed extends BaseReport
{

    public
        $clientId,
        $processIds;

    public function setReportRefs()
    {
        $this->reportName = "Client_Statement_Detailed_Report";
        $this->reportView = 'reports.client.account-statement.detailed';
        $this->cssPath = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.client.accountStatement.printDetailedPDF';
    }

    public function getReportData()
    {
        $client = Client::findOrFail($this->clientId);
        $clientName = $client->name;
        $allProcessesTotalPrice = 0;
        $allProcessTotalPaid = 0;
        $allProcessTotalRemaining = 0;
        $date = DateTime::todayDateformat();
        $processes = [];

        foreach ($this->processIds as $id) {
            $clientProcess = ClientProcess::findOrFail($id);
            $processes[$id]['processName'] = $clientProcess->name;

            $processes[$id]['processTotalPrice'] = $clientProcess->total_price;
            $processes[$id]['processTotalPaid'] = $clientProcess->totalDeposits() + $clientProcess->discount_value;
            $processes[$id]['processTotalRemaining'] = $clientProcess->total_price_taxes - $clientProcess->totalDeposits();
            $processes[$id]['processDate'] = DateTime::todayDateformat(); //Print Date
            $processes[$id]['processNum'] = $id;
            $allProcessesTotalPrice += $processes[$id]['processTotalPrice'];
            $allProcessTotalPaid += $processes[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $processes[$id]['processTotalRemaining'];

            $index = 0;
            $totalDepositValue = 0;
            foreach ($clientProcess->items as $item) {
                $processes[$id]['processDetails'][$index]['date'] = DateTime::parseToDateFormat($item->created_at);
                $processes[$id]['processDetails'][$index]['remaining'] = "";
                $processes[$id]['processDetails'][$index]['paid'] = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                $processes[$id]['processDetails'][$index]['unitPrice'] = $item->unit_price;
                $processes[$id]['processDetails'][$index]['quantity'] = $item->quantity;
                $processes[$id]['processDetails'][$index]['desc'] = $item->description;
                $index++;
            }
            if ($clientProcess->has_discount == TRUE) {
                $processes[$id]['processDetails'][$index]['date'] = DateTime::parseToDateFormat($clientProcess->created_at);
                $processes[$id]['processDetails'][$index]['remaining'] = "";
                $processes[$id]['processDetails'][$index]['paid'] = $clientProcess->discount_value;
                $processes[$id]['processDetails'][$index]['totalPrice'] = "";
                $processes[$id]['processDetails'][$index]['unitPrice'] = "";
                $processes[$id]['processDetails'][$index]['quantity'] = "";
                $processes[$id]['processDetails'][$index]['desc'] = "خصم بسبب : " . $clientProcess->discount_reason;
                $index++;
            }
            if ($clientProcess->has_source_discount == TRUE) {
                $processes[$id]['processDetails'][$index]['date'] = DateTime::parseToDateFormat($clientProcess->created_at);
                $processes[$id]['processDetails'][$index]['remaining'] = "";
                $processes[$id]['processDetails'][$index]['paid'] = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->source_discount_value;
                $processes[$id]['processDetails'][$index]['unitPrice'] = "";
                $processes[$id]['processDetails'][$index]['quantity'] = "";
                $processes[$id]['processDetails'][$index]['desc'] = "خصم من المنبع";
                $index++;
            }
            if ($clientProcess->require_invoice == TRUE) {
                $processes[$id]['processDetails'][$index]['date'] = DateTime::parseToDateFormat($clientProcess->created_at);
                $processes[$id]['processDetails'][$index]['remaining'] = "";
                $processes[$id]['processDetails'][$index]['paid'] = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->taxesValue();
                $processes[$id]['processDetails'][$index]['unitPrice'] = "";
                $processes[$id]['processDetails'][$index]['quantity'] = "";
                $processes[$id]['processDetails'][$index]['desc'] = "قيمة الضريبة المضافة";
                $index++;
            }
            foreach ($clientProcess->deposits as $deposit) {
                $totalDepositValue += $deposit->depositValue;
                $processes[$id]['processDetails'][$index]['date'] = DateTime::parseToDateFormat($deposit->due_date);
                $processes[$id]['processDetails'][$index]['remaining'] = $clientProcess->total_price_taxes - $totalDepositValue;
                $processes[$id]['processDetails'][$index]['paid'] = $deposit->depositValue;
                $processes[$id]['processDetails'][$index]['totalPrice'] = "";
                $processes[$id]['processDetails'][$index]['unitPrice'] = "";
                $processes[$id]['processDetails'][$index]['quantity'] = "";
                $processes[$id]['processDetails'][$index]['desc'] = $deposit->recordDesc;
                $index++;
            }
        }
        $data = [
            'clientName'               => $clientName,
            'date'                     => $date,
            'processes'                => $processes,
            'allProcessesTotalPrice'   => $allProcessesTotalPrice,
            'allProcessTotalPaid'      => $allProcessTotalPaid,
            'allProcessTotalRemaining' => $allProcessTotalRemaining
        ];
        return $data;
    }
}
