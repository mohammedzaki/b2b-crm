<?php

namespace App\Reports\V2\Supplier;

use App\Extensions\DateTime;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use App\Reports\V2\BaseReport;

class SupplierDetailed extends BaseReport
{

    public
        $supplierId,
        $processIds;

    public function __construct($withLetterHead = true, $supplierId = null, $processIds = null)
    {
        parent::__construct($withLetterHead);
        $this->processIds = $processIds;
        $this->supplierId = $supplierId;
    }

    public function setReportRefs()
    {
        $this->reportName       = 'Supplier_Statement_Detailed_Report';
        $this->reportView       = 'reports.supplier.account-statement.detailed';
        $this->cssPath          = 'reports/css/client/detailed.css';
        $this->printRouteAction = 'reports.supplier.accountStatement.printPDF';
    }

    public function getReportData($withUserLog = false)
    {
        $supplier                 = Supplier::findOrFail($this->supplierId);
        $supplierName             = $supplier->name;
        $allProcessesTotalPrice   = 0;
        $allProcessTotalPaid      = 0;
        $allProcessTotalRemaining = 0;
        $date                     = DateTime::todayDateformat();

        $processes = [];
        foreach ($this->processIds as $id) {
            $supplierProcess                         = SupplierProcess::findOrFail($id);
            $processes[$id]['processName']           = $supplierProcess->name;
            $processes[$id]['processTotalPrice']     = $supplierProcess->total_price;
            $processes[$id]['processTotalPaid']      = $supplierProcess->totalWithdrawals() + $supplierProcess->discount_value;
            $processes[$id]['processTotalRemaining'] = $supplierProcess->total_price_taxes - $supplierProcess->totalWithdrawals();
            $processes[$id]['processDate']           = DateTime::today()->format('Y-m-d'); //DateTime::parse($supplierProcess->created_at)->format('Y-m-d');
            $processes[$id]['processNum']            = $id;
            $allProcessesTotalPrice                  += $processes[$id]['processTotalPrice'];
            $allProcessTotalPaid                     += $processes[$id]['processTotalPaid'];
            $allProcessTotalRemaining                += $processes[$id]['processTotalRemaining'];

            $index                = 0;
            $totalWithdrawalValue = 0;
            foreach ($supplierProcess->items as $item) {
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parse($item->created_at)->format('Y-m-d');
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                $processes[$id]['processDetails'][$index]['unitPrice']  = $item->unit_price;
                $processes[$id]['processDetails'][$index]['quantity']   = $item->quantity;
                $processes[$id]['processDetails'][$index]['desc']       = $item->description;
                $index++;
            }
            if ($supplierProcess->has_discount == TRUE) {
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parse($item->created_at)->format('Y-m-d');
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = $supplierProcess->discount_value;
                $processes[$id]['processDetails'][$index]['totalPrice'] = "";
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = "خصم بسبب : " . $supplierProcess->discount_reason;
                $index++;
            }
            if ($supplierProcess->has_source_discount == TRUE) {
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parse($supplierProcess->created_at)->format('Y-m-d');
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $supplierProcess->source_discount_value;
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = "خصم من المنبع";
                $index++;
            }
            if ($supplierProcess->require_invoice == TRUE) {
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parse($item->created_at)->format('Y-m-d');
                $processes[$id]['processDetails'][$index]['remaining']  = "";
                $processes[$id]['processDetails'][$index]['paid']       = "";
                $processes[$id]['processDetails'][$index]['totalPrice'] = $supplierProcess->taxesValue();
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = "قيمة الضريبة المضافة";
                $index++;
            }
            $items = $withUserLog ? $supplierProcess->withdrawalsWithTrashed() : $supplierProcess->withdrawals();
            foreach ($items as $withdrawal) {
                $totalWithdrawalValue                                   += $withdrawal->withdrawValue;
                $processes[$id]['processDetails'][$index]['pending']    = $withdrawal->pendingStatus;
                $processes[$id]['processDetails'][$index]['date']       = DateTime::parse($withdrawal->due_date)->format('Y-m-d');
                $processes[$id]['processDetails'][$index]['remaining']  = $supplierProcess->total_price_taxes - $totalWithdrawalValue;
                $processes[$id]['processDetails'][$index]['paid']       = $withdrawal->withdrawValue;
                $processes[$id]['processDetails'][$index]['totalPrice'] = "";
                $processes[$id]['processDetails'][$index]['unitPrice']  = "";
                $processes[$id]['processDetails'][$index]['quantity']   = "";
                $processes[$id]['processDetails'][$index]['desc']       = $withdrawal->recordDesc;
                if($withUserLog) {
                    $processes[$id]['processDetails'][$index]['deleted'] = $withdrawal->deleted_at;
                    $processes[$id]['processDetails'][$index]['id']      = $withdrawal->id;
                }
                $index++;
            }
            //dd($processes);
        }

        $data = [
            'supplierName'             => $supplierName,
            'date'                     => $date,
            'processes'                => $processes,
            'allProcessesTotalPrice'   => $allProcessesTotalPrice,
            'allProcessTotalPaid'      => $allProcessTotalPaid,
            'allProcessTotalRemaining' => $allProcessTotalRemaining
        ];
        return $data;
    }
}
