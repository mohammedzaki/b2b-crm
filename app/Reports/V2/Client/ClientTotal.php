<?php

namespace App\Reports\V2\Client;

use App\Models\Client;
use App\Models\ClientProcess;
use App\Reports\V2\BaseReport;
use App\Extensions\DateTime;


class ClientTotal extends BaseReport
{

    public
        $clientId,
        $processIds;

    public function setReportRefs()
    {
        $this->reportName = "Client_Statement_Total_Report";
        $this->reportView = 'reports.client.account-statement.total';
        $this->cssPath = 'css/app.css';
        $this->printRouteAction = 'reports.client.accountStatement.printTotalPDF';
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
