<?php

namespace App\Reports\V2\Supplier;

class SupplierTotal extends SupplierDetailed
{

    public function setReportRefs()
    {
        $this->reportName       = 'Supplier_Statement_Total_Report';
        $this->reportView       = 'reports.supplier.account-statement.total';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.supplier.accountStatement.printPDF';
    }
}
