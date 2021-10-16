<?php

namespace App\Reports\V2\Client;

class ClientTotal extends ClientDetailed
{

    public function setReportRefs()
    {
        $this->reportName       = 'Client_Statement_Total_Report';
        $this->reportView       = 'reports.client.account-statement.total';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.client.accountStatement.printPDF';
    }
}
