<?php

namespace App\Reports\V2\Supplier;

class SupplierAnalyticalCenterTotal extends SupplierAnalyticalCenterDetailed
{

    public function setReportRefs()
    {
        $this->reportName       = 'Supplier_Analytical_Center_Total_Report';
        $this->reportView       = 'reports.supplier.analytical-center.total';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.supplier.analyticalCenter.printPDF';
    }

}
