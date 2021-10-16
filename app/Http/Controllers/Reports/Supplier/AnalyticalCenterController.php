<?php

namespace App\Http\Controllers\Reports\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Reports\V2\Supplier\SupplierAnalyticalCenterDetailed;
use App\Reports\V2\Supplier\SupplierAnalyticalCenterTotal;
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

        return view('reports.supplier.analytical-center.index', compact("suppliers"));
    }

    /**
     * Show the ViewReport Page
     * @Get("/view-report", as="reports.supplier.analyticalCenter.viewReport")
     */
    public function viewReport(Request $request)
    {
        return $this->getReport($request)->preview();
    }

    /**
     * Show the PrintPDF Page
     * @Get("/print-pdf", as="reports.supplier.analyticalCenter.printPDF")
     */
    public function printPDF(Request $request)
    {
        return $this->getReport($request)->exportPDF();
    }

    private function getReport(Request $request)
    {
        if ($request->ch_detialed == FALSE) {
            return new SupplierAnalyticalCenterTotal($request->withLetterHead, $request->selectedIds);
        } else {
            return new SupplierAnalyticalCenterDetailed($request->withLetterHead, $request->selectedIds);
        }
    }
}
