<?php

namespace App\Reports\V2\Client;


class ClientAnalyticalCenterTotal extends ClientAnalyticalCenterDetailed
{
    public function setReportRefs()
    {
        $this->reportName       = 'Client_Analytical_Center_Total_Report';
        $this->reportView       = 'reports.client.analytical-center.total';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.client.analyticalCenter.printPDF';
    }
}
