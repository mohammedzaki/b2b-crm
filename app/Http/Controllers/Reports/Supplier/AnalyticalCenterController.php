<?php

namespace App\Http\Controllers\Reports\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Reports\Supplier\SupplierAnalyticalCenterDetailed;
use App\Reports\Supplier\SupplierAnalyticalCenterTotal;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="/reports/supplier/analytical-center")
 * @Middleware({"web", "auth"})
 */
class AnalyticalCenterController extends Controller
{

    /**
     * Show the Index Page
     * @Get("/", as="reports.supplier.analyticalCenter.index")
     */
    public function index()
    {
        $suppliers     = Supplier::allHasOpenProcess();
        $suppliers_tmp = [];
        $index         = 0;
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$index]['id']             = $supplier->id;
            $suppliers_tmp[$index]['name']           = $supplier->name;
            $suppliers_tmp[$index]['totalDeal']      = $supplier->getTotalDeal();
            $suppliers_tmp[$index]['totalPaid']      = $supplier->getTotalPaid();
            $suppliers_tmp[$index]['totalRemaining'] = $supplier->getTotalRemaining();
            $index++;
        }
        $suppliers = $suppliers_tmp;

        return view('reports.Supplier.AnalyticalCenter.index', compact("suppliers"));
    }

    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.supplier.analyticalCenter.viewReport")
     */
    public function viewReport(Request $request)
    {
        // {"ch_detialed":"0","supplier_id":"1","processes":["1","2"]}
        $supplierName               = "";
        $allSuppliersTotalPrice     = 0;
        $allSuppliersTotalPaid      = 0;
        $allSuppliersTotalRemaining = 0;

        $suppliers = [];
        foreach ($request->selectedIds as $id) {
            $supplier                                 = Supplier::findOrFail($id);
            $suppliers[$id]['supplierName']           = $supplier->name;
            $suppliers[$id]['supplierNum']            = $supplier->id;
            $suppliers[$id]['supplierTotalPrice']     = 0;
            $suppliers[$id]['supplierTotalPaid']      = 0;
            $suppliers[$id]['supplierTotalRemaining'] = 0;
            $index                                    = 0;
            $suppliers[$id]['processDetails']         = [];
            foreach ($supplier->processes as $process) {
                if ($request->ch_detialed == TRUE) {
                    $suppliers[$id]['processDetails'][$index]['name']       = $process->name;
                    $suppliers[$id]['processDetails'][$index]['totalPrice'] = $process->total_price_taxes;
                    $suppliers[$id]['processDetails'][$index]['paid']       = $process->totalWithdrawals();
                    $suppliers[$id]['processDetails'][$index]['remaining']  = $process->totalRemaining();
                    $suppliers[$id]['processDetails'][$index]['date']       = $process->created_at;
                }
                $suppliers[$id]['supplierTotalPrice']     += $process->total_price_taxes;
                $suppliers[$id]['supplierTotalPaid']      += $process->totalWithdrawals();
                $suppliers[$id]['supplierTotalRemaining'] += $process->totalRemaining();
                $index++;
            }
            $allSuppliersTotalPrice     += $suppliers[$id]['supplierTotalPrice'];
            $allSuppliersTotalPaid      += $suppliers[$id]['supplierTotalPaid'];
            $allSuppliersTotalRemaining += $suppliers[$id]['supplierTotalRemaining'];
        }

        session([
                    'supplierName'               => "",
                    'suppliers'                  => $suppliers,
                    'allSuppliersTotalPrice'     => $allSuppliersTotalPrice,
                    'allSuppliersTotalPaid'      => $allSuppliersTotalPaid,
                    'allSuppliersTotalRemaining' => $allSuppliersTotalRemaining
                ]);
        if ($request->ch_detialed == FALSE) {
            return view("reports.Supplier.AnalyticalCenter.total", compact('supplierName', 'suppliers', 'allSuppliersTotalPrice', 'allSuppliersTotalPaid', 'allSuppliersTotalRemaining'));
        } else {
            return view("reports.Supplier.AnalyticalCenter.detialed", compact('supplierName', 'suppliers', 'allSuppliersTotalPrice', 'allSuppliersTotalPaid', 'allSuppliersTotalRemaining'));
        }
    }

    /**
     * Show the Index Page
     * @Get("/print-total-pdf", as="reports.supplier.analyticalCenter.printTotalPDF")
     */
    public function printTotalPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('suppliers'), session('allSuppliersTotalPrice'), session('allSuppliersTotalPaid'), session('allSuppliersTotalRemaining'));
    }

    private function exportPDF($ch_detialed, $withLetterHead, $supplierName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining)
    {
        if ($ch_detialed == FALSE) {
            $pdfReport = new SupplierAnalyticalCenterTotal($withLetterHead);
        } else {
            $pdfReport = new SupplierAnalyticalCenterDetailed($withLetterHead);
        }
        $pdfReport->supplierName             = $supplierName;
        $pdfReport->proceses                 = $proceses;
        $pdfReport->allProcessesTotalPrice   = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid      = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->exportPDF();
    }

    /**
     * Show the Index Page
     * @Get("/print-detailed-pdf", as="reports.supplier.analyticalCenter.printDetailedPDF")
     */
    public function printDetailedPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('suppliers'), session('allSuppliersTotalPrice'), session('allSuppliersTotalPaid'), session('allSuppliersTotalRemaining'));
    }

}
