<?php

namespace App\Reports\V2\Client;

use App\Models\Client;
use App\Reports\V2\BaseReport;
use App\Extensions\DateTime;

class ClientAnalyticalCenterDetailed extends BaseReport
{

    public
        $clientIds;

    public function __construct($withLetterHead = true, $clientIds = null)
    {
        parent::__construct($withLetterHead);
        $this->clientIds = $clientIds;
    }

    public function setReportRefs()
    {
        $this->reportName       = 'Client_Analytical_Center_Detailed_Report';
        $this->reportView       = 'reports.client.analytical-center.detailed';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.client.analyticalCenter.printPDF';
    }

    public function getReportData($withUserLog = false)
    {
        $allClientsTotalPrice     = 0;
        $allClientsTotalPaid      = 0;
        $allClientsTotalRemaining = 0;
        $date                     = DateTime::todayDateformat();

        $clients = [];
        foreach ($this->clientIds as $id) {
            $client                               = Client::findOrFail($id);
            $clients[$id]['clientName']           = $client->name;
            $clients[$id]['clientNum']            = $client->id;
            $clients[$id]['clientTotalPrice']     = 0;
            $clients[$id]['clientTotalPaid']      = 0;
            $clients[$id]['clientTotalRemaining'] = 0;
            $index                                = 0;
            $clients[$id]['processDetails']       = [];
            foreach ($client->processes as $process) {

                $clients[$id]['processDetails'][$index]['name']       = $process->name;
                $clients[$id]['processDetails'][$index]['totalPrice'] = $process->total_price_taxes;
                $clients[$id]['processDetails'][$index]['paid']       = $process->totalDeposits();
                $clients[$id]['processDetails'][$index]['remaining']  = $process->totalRemaining();
                $clients[$id]['processDetails'][$index]['date']       = $process->created_at;

                $clients[$id]['clientTotalRemaining'] += $process->totalRemaining();
                $clients[$id]['clientTotalPaid']      += $process->totalDeposits();
                $clients[$id]['clientTotalPrice']     += $process->total_price_taxes;
                $index++;
            }

            $allClientsTotalPrice     += $clients[$id]['clientTotalPrice'];
            $allClientsTotalPaid      += $clients[$id]['clientTotalPaid'];
            $allClientsTotalRemaining += $clients[$id]['clientTotalRemaining'];

        }

        $data = [
            // 'clientName'               => $clientName,
            'date'                     => $date,
            'clients'                  => $clients,
            'allClientsTotalPrice'     => $allClientsTotalPrice,
            'allClientsTotalPaid'      => $allClientsTotalPaid,
            'allClientsTotalRemaining' => $allClientsTotalRemaining
        ];
        return $data;
    }
}
