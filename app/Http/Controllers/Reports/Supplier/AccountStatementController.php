<?php

namespace App\Http\Controllers\Reports\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Reports\V2\Supplier\SupplierDetailed;
use App\Reports\V2\Supplier\SupplierTotal;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="/reports/supplier/account-statement")
 * @Middleware({"web", "auth"})
 */
class AccountStatementController extends Controller
{

    /**
     * Show the Index Page
     * @Get("/", as="reports.supplier.accountStatement.index")
     */
    public function index()
    {
        $suppliers         = Supplier::all();
        $suppliers_tmp     = [];
        $supplierProcesses = [];
        $index             = 0;
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$index]['id']               = $supplier->id;
            $suppliers_tmp[$index]['name']             = $supplier->name;
            $suppliers_tmp[$index]['hasOpenProcess']   = $supplier->hasOpenProcess();
            $suppliers_tmp[$index]['hasClosedProcess'] = $supplier->hasClosedProcess();

            foreach ($supplier->processes as $process) {
                $supplierProcesses[$supplier->id][$process->id]['name']       = $process->name;
                $supplierProcesses[$supplier->id][$process->id]['totalPrice'] = $process->total_price;
                $supplierProcesses[$supplier->id][$process->id]['status']     = $process->status;
            }
            $index++;
        }
        $suppliers         = json_encode($suppliers_tmp);
        $supplierProcesses = json_encode($supplierProcesses);
        return view('reports.supplier.account-statement.index', compact("suppliers", "supplierProcesses"));
    }

    /**
     * Show the ViewReport Page
     * @Get("/view-report", as="reports.supplier.accountStatement.viewReport")
     */
    public function viewReport(Request $request)
    {
        return $this->getReport($request)->preview();
    }

    /**
     * Show the PrintPDF Page
     * @Get("/print-pdf", as="reports.supplier.accountStatement.printPDF")
     */
    public function printPDF(Request $request)
    {
        return $this->getReport($request)->exportPDF();
    }

    private function getReport(Request $request)
    {
        if ($request->ch_detialed == FALSE) {
            return new SupplierTotal($request->withLetterHead, $request->supplier_id, $request->processes);
        } else {
            return new SupplierDetailed($request->withLetterHead, $request->supplier_id, $request->processes);
        }
    }

}
