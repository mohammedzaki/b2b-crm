<?php

namespace App\Reports\V2\Supplier;

use App\Extensions\DateTime;
use App\Models\Supplier;
use App\Reports\V2\BaseReport;

class SupplierAnalyticalCenterDetailed extends BaseReport
{

    public
        $supplierIds;

    public function __construct($withLetterHead = true, $supplierIds = null)
    {
        parent::__construct($withLetterHead);
        $this->supplierIds = $supplierIds;
    }

    public function setReportRefs()
    {
        $this->reportName       = 'Supplier_Analytical_Center_Detailed_Report';
        $this->reportView       = 'reports.supplier.analytical-center.detailed';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.supplier.analyticalCenter.printPDF';
    }

    public function getReportData($withUserLog = false)
    {
        $allSuppliersTotalPrice     = 0;
        $allSuppliersTotalPaid      = 0;
        $allSuppliersTotalRemaining = 0;
        $date                       = DateTime::todayDateformat();

        $suppliers = [];
        foreach ($this->supplierIds as $id) {
            $supplier                                 = Supplier::findOrFail($id);
            $suppliers[$id]['supplierName']           = $supplier->name;
            $suppliers[$id]['supplierNum']            = $supplier->id;
            $suppliers[$id]['supplierTotalPrice']     = 0;
            $suppliers[$id]['supplierTotalPaid']      = 0;
            $suppliers[$id]['supplierTotalRemaining'] = 0;
            $index                                    = 0;
            $suppliers[$id]['processDetails']         = [];
            foreach ($supplier->processes as $process) {
                $suppliers[$id]['processDetails'][$index]['name']       = $process->name;
                $suppliers[$id]['processDetails'][$index]['totalPrice'] = $process->total_price_taxes;
                $suppliers[$id]['processDetails'][$index]['paid']       = $process->totalWithdrawals();
                $suppliers[$id]['processDetails'][$index]['remaining']  = $process->totalRemaining();
                $suppliers[$id]['processDetails'][$index]['date']       = $process->created_at;
                $suppliers[$id]['supplierTotalPrice']                   += $process->total_price_taxes;
                $suppliers[$id]['supplierTotalPaid']                    += $process->totalWithdrawals();
                $suppliers[$id]['supplierTotalRemaining']               += $process->totalRemaining();
                $index++;
            }
            $allSuppliersTotalPrice     += $suppliers[$id]['supplierTotalPrice'];
            $allSuppliersTotalPaid      += $suppliers[$id]['supplierTotalPaid'];
            $allSuppliersTotalRemaining += $suppliers[$id]['supplierTotalRemaining'];
        }

        $data = [
            'date'                       => $date,
            'suppliers'                  => $suppliers,
            'allSuppliersTotalPrice'     => $allSuppliersTotalPrice,
            'allSuppliersTotalPaid'      => $allSuppliersTotalPaid,
            'allSuppliersTotalRemaining' => $allSuppliersTotalRemaining
        ];
        return $data;
    }
}
