<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use App\Models\DepositWithdraw;
use App\Extensions\DateTime;
use App\Reports\Client\ClientTotal;
use App\Reports\Client\ClientDetailed;
use App\Reports\Supplier\SupplierTotal;
use App\Reports\Supplier\SupplierDetailed;
use App\Http\Controllers\FacilityController;

class SupplierReportsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //$this->middleware('ability:admin,employees-permissions');
    }

    protected function validator(array $data) {
        $validator = Validator::make($data, [
                    'name' => 'required|min:6|max:255',
                    'ssn' => 'required|digits:14',
                    'gender' => 'required|in:m,f',
                    'martial_status' => 'in:single,married,widowed,divorced',
                    'birth_date' => 'required|date_format:Y-m-d',
                    'department' => 'string',
                    'hiring_date' => 'required|date_format:Y-m-d',
                    'daily_salary' => 'required|numeric',
                    'working_hours' => 'required|numeric',
                    'job_title' => 'required|max:100',
                    'telephone' => 'digits:8',
                    'mobile' => 'required|digits:11',
                    'can_not_use_program' => 'boolean',
                    'is_active' => 'boolean',
                    'borrow_system' => 'boolean',
                    'username' => 'unique:users,username',
                    'password' => 'min:4'
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم الموظف',
            'ssn' => 'الرقم القومي',
            'gender' => 'الجنس',
            'martial_status' => 'الحالة اﻻجتماعية',
            'birth_date' => 'تاريخ الميﻻد',
            'department' => 'القسم',
            'hiring_date' => 'تاريخ التعيين',
            'daily_salary' => 'الراتب اليومي',
            'working_hours' => 'ساعات العمل',
            'job_title' => 'الوظيفة',
            'telephone' => 'التليفون',
            'mobile' => 'المحمول',
            'can_not_use_program' => 'عدم استخدام البرنامج',
            'is_active' => 'نشط',
            'borrow_system' => 'نظام السلف',
            'username' => 'اسم المستخدم',
            'password' => 'كلمة المرور'
        ]);

        return $validator;
    }
    
    public function index() {
        $suppliers = Supplier::all();
        $suppliers_tmp = [];
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        $suppliers = $suppliers_tmp;
        
        $supplierProcesses = SupplierProcess::all();

        $supplierProcesses_tmp = [];
        foreach ($supplierProcesses as $process) {
            $supplierProcesses_tmp[$process->id]['supplierId'] = $process->supplier->id;
            $supplierProcesses_tmp[$process->id]['name'] = $process->name;
            $supplierProcesses_tmp[$process->id]['totalPrice'] = $process->total_price;
            $supplierProcesses_tmp[$process->id]['status'] = $process->status;
        }
        $supplierProcesses = $supplierProcesses_tmp;
        return view('reports.supplier.index', compact("suppliers", "supplierProcesses"));
    }

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
            $proceses[$id]['processTotalPrice'] = $supplierProcess->total_price_taxes;
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
                
                foreach ($supplierProcess->withdrawals() as $withdrawal) {
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
            return view("reports.supplier.total", compact('supplierName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        } else {
            return view("reports.supplier.detialed", compact('supplierName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        }
    }

    private function printPDF($ch_detialed, $withLetterHead, $supplierName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
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
        return $pdfReport->RenderReport();
    }

    public function printTotalPDF(Request $request) {
        return $this->printSupplierPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    public function printDetailedPDF(Request $request) {
        return $this->printSupplierPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'),  session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

}
