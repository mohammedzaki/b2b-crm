<?php

namespace App\Http\Controllers\Reports\Supplier;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use App\Reports\Supplier\SupplierDetailed;
use App\Reports\Supplier\SupplierTotal;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="/reports/supplier/account-statement")
 * @Middleware({"web", "auth"})
 */
class AccountStatementController extends Controller {

    /**
     * Show the Index Page
     * @Get("/", as="reports.supplier.accountStatement.index")
     */
    public function index() {
        $suppliers = Supplier::all();
        $suppliers_tmp = [];
        $supplierProcesses = [];
        $index = 0;
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$index]['id'] = $supplier->id;
            $suppliers_tmp[$index]['name'] = $supplier->name;
            $suppliers_tmp[$index]['hasOpenProcess'] = $supplier->hasOpenProcess();
            $suppliers_tmp[$index]['hasClosedProcess'] = $supplier->hasClosedProcess();
            
            foreach ($supplier->processes as $process) {
                $supplierProcesses[$supplier->id][$process->id]['name'] = $process->name;
                $supplierProcesses[$supplier->id][$process->id]['totalPrice'] = $process->total_price;
                $supplierProcesses[$supplier->id][$process->id]['status'] = $process->status;
            }
            $index++;
        }
        $suppliers = json_encode($suppliers_tmp);

        $supplierProcesses = json_encode($supplierProcesses);

        return view('reports.Supplier.AccountStatement.index', compact("suppliers", "supplierProcesses"));
    }
    
    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.supplier.accountStatement.viewReport")
     */
    public function viewReport(Request $request) {
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplierName = $supplier->name;
        $allProcessesTotalPrice = 0;
        $allProcessTotalPaid = 0;
        $allProcessTotalRemaining = 0;

        $proceses = [];
        foreach ($request->processes as $id) {
            $supplierProcess = SupplierProcess::findOrFail($id);
            $proceses[$id]['processName'] = $supplierProcess->name;
            $proceses[$id]['processTotalPrice'] = $supplierProcess->total_price;
            $proceses[$id]['processTotalPaid'] = $supplierProcess->totalWithdrawals() + $supplierProcess->discount_value;
            $proceses[$id]['processTotalRemaining'] = $supplierProcess->total_price_taxes - $supplierProcess->totalWithdrawals();
            $proceses[$id]['processDate'] = DateTime::today()->format('Y-m-d'); //DateTime::parse($supplierProcess->created_at)->format('Y-m-d');
            $proceses[$id]['processNum'] = $id;
            $allProcessesTotalPrice += $proceses[$id]['processTotalPrice'];
            $allProcessTotalPaid += $proceses[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $proceses[$id]['processTotalRemaining'];

            if ($request->ch_detialed == TRUE) {
                $index = 0;
                $totalWithdrawalValue = 0;
                foreach ($supplierProcess->items as $item) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['quantity'] = $item->quantity;
                    $proceses[$id]['processDetails'][$index]['desc'] = $item->description;
                    $index++;
                }
                if ($supplierProcess->has_discount == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = $supplierProcess->discount_value;
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم بسبب : " . $supplierProcess->discount_reason;
                    $index++;
                }
                if ($supplierProcess->has_source_discount == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($supplierProcess->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $supplierProcess->source_discount_value;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم من المنبع";
                    $index++;
                }
                if ($supplierProcess->has_source_discount == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($supplierProcess->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $supplierProcess->source_discount_value;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم من المنبع";
                    $index++;
                }
                if ($supplierProcess->require_invoice == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $supplierProcess->taxesValue();
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "قيمة الضريبة المضافة";
                    $index++;
                }
                
                foreach ($supplierProcess->withdrawals as $withdrawal) {
                    $totalWithdrawalValue += $withdrawal->withdrawValue;
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($withdrawal->due_date)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = $supplierProcess->total_price_taxes - $totalWithdrawalValue;
                    $proceses[$id]['processDetails'][$index]['paid'] = $withdrawal->withdrawValue;
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = $withdrawal->recordDesc;
                    $index++;
                }
            }
        }
        session([
            'supplierName' => $supplierName,
            'proceses' => $proceses,
            'allProcessesTotalPrice' => $allProcessesTotalPrice,
            'allProcessTotalPaid' => $allProcessTotalPaid,
            'allProcessTotalRemaining' => $allProcessTotalRemaining
        ]);
        if ($request->ch_detialed == FALSE) {
            return view("reports.Supplier.AccountStatement.total", compact('supplierName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        } else {
            return view("reports.Supplier.AccountStatement.detialed", compact('supplierName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        }
    }

    /**
     * Show the Index Page
     * @Get("/print-total-pdf", as="reports.supplier.accountStatement.printTotalPDF")
     */
    public function printTotalPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    /**
     * Show the Index Page
     * @Get("/print-detailed-pdf", as="reports.supplier.accountStatement.printDetailedPDF")
     */
    public function printDetailedPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'),  session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }
    
    private function exportPDF($ch_detialed, $withLetterHead, $supplierName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
        if ($ch_detialed == FALSE) {
            $pdfReport = new SupplierTotal($withLetterHead);
        } else {
            $pdfReport = new SupplierDetailed($withLetterHead);
        }
        $pdfReport->supplierName = $supplierName;
        $pdfReport->proceses = $proceses;
        $pdfReport->allProcessesTotalPrice = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->exportPDF();
    }

}
