<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Client;
use App\ClientProcess;
use App\Supplier;
use App\SupplierProcess;
use App\DepositWithdraw;
use Carbon\Carbon;
use App\Reports\Client\ClientTotal;
use App\Reports\Client\ClientDetailed;
use App\Reports\Supplier\SupplierTotal;
use App\Reports\Supplier\SupplierDetailed;
use App\Http\Controllers\FacilityController;

class ReportsController extends Controller {

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
        return view('reports.index');
    }

    public function client() {
        $clients = Client::all();
        $clients_tmp = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        $clients = $clients_tmp;
        
        $clientProcesses = ClientProcess::all();

        $clientProcesses_tmp = [];
        foreach ($clientProcesses as $process) {
            $clientProcesses_tmp[$process->id]['clientId'] = $process->client->id;
            $clientProcesses_tmp[$process->id]['name'] = $process->name;
            $clientProcesses_tmp[$process->id]['totalPrice'] = $process->total_price;
            $clientProcesses_tmp[$process->id]['status'] = $process->status;
        }
        $clientProcesses = $clientProcesses_tmp;
        
        return view('reports.client.index', compact("clients", "clientProcesses"));
    }

    public function viewClientReport(Request $request) {
        //{"ch_detialed":"0","client_id":"1","processes":["1","2"]}
        $client = Client::findOrFail($request->client_id);
        $clientName = $client->name;
        $allProcessesTotalPrice = 0;
        $allProcessTotalPaid = 0;
        $allProcessTotalRemaining = 0;

        $proceses = [];
        foreach ($request->processes as $id) {
            $clientProcess = ClientProcess::findOrFail($id);
            $proceses[$id]['processName'] = $clientProcess->name;
            
            $proceses[$id]['processTotalPrice'] = $clientProcess->totalPriceAfterTaxes() + $clientProcess->discountValue();
            $proceses[$id]['processTotalPaid'] = $clientProcess->totalDeposits() + $clientProcess->discountValue();
            $proceses[$id]['processTotalRemaining'] = $clientProcess->totalPriceAfterTaxes() - $clientProcess->totalDeposits();
            $proceses[$id]['processDate'] = Carbon::today()->format('Y-m-d'); //Carbon::parse($clientProcess->created_at)->format('Y-m-d');
            $proceses[$id]['processNum'] = $id;
            $allProcessesTotalPrice += $proceses[$id]['processTotalPrice'];
            $allProcessTotalPaid += $proceses[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $proceses[$id]['processTotalRemaining'];

            if ($request->ch_detialed == "1") {
                $index = 0;
                $totalDepositValue = 0;
                foreach ($clientProcess->items as $item) {
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['quantity'] = $item->quantity;
                    $proceses[$id]['processDetails'][$index]['desc'] = $item->description;
                    $index++;
                }
                if ($clientProcess->has_discount == "1") {
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = $clientProcess->discountValue();
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم بسبب : " . $clientProcess->discount_reason;
                    $index++;
                }
                //$proceses[$id]['processTotalPaid'] += $discount;
                if ($clientProcess->require_bill == "1") {
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->taxesValue();
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "قيمة الضريبة المضافة";
                    $index++;
                }
                
                
                foreach ($clientProcess->deposits()->where('client_id', $clientProcess->client->id)->get() as $deposit) {
                    $totalDepositValue += $deposit->depositValue;
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($deposit->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = $clientProcess->totalPriceAfterTaxes() - $totalDepositValue;
                    $proceses[$id]['processDetails'][$index]['paid'] = $deposit->depositValue;
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = $deposit->recordDesc;
                    $index++;
                }
            }
        }

        session([
            'clientName' => $clientName,
            'proceses' => $proceses,
            'allProcessesTotalPrice' => $allProcessesTotalPrice,
            'allProcessTotalPaid' => $allProcessTotalPaid,
            'allProcessTotalRemaining' => $allProcessTotalRemaining
        ]);
        if ($request->ch_detialed == "0") {
            return view("reports.client.total", compact('clientName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        } else {
            return view("reports.client.detialed", compact('clientName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        }
    }

    public function printTotalPDF(Request $request) {
        return $this->printClientPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    public function printDetailedPDF(Request $request) {
        return $this->printClientPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    private function printClientPDF($ch_detialed, $withLetterHead, $clientName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
        if ($ch_detialed == "0") {
            $pdfReport = new ClientTotal($withLetterHead);
        } else {
            $pdfReport = new ClientDetailed($withLetterHead);
        }
        $pdfReport->clientName = $clientName;
        $pdfReport->proceses = $proceses;
        $pdfReport->allProcessesTotalPrice = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->RenderReport();
    }

    public function supplier() {
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

    public function viewSupplierReport(Request $request) {
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplierName = $supplier->name;
        $allProcessesTotalPrice = 0;
        $allProcessTotalPaid = 0;
        $allProcessTotalRemaining = 0;

        $proceses = [];
        foreach ($request->processes as $id) {
            $supplierProcess = SupplierProcess::findOrFail($id);
            $proceses[$id]['processName'] = $supplierProcess->name;
            $proceses[$id]['processTotalPrice'] = $supplierProcess->totalPriceAfterTaxes() + $supplierProcess->discountValue();
            $proceses[$id]['processTotalPaid'] = $supplierProcess->totalWithdrawals() + $supplierProcess->discountValue();
            $proceses[$id]['processTotalRemaining'] = $supplierProcess->totalPriceAfterTaxes() - $supplierProcess->totalWithdrawals();
            $proceses[$id]['processDate'] = Carbon::today()->format('Y-m-d'); //Carbon::parse($supplierProcess->created_at)->format('Y-m-d');
            $proceses[$id]['processNum'] = $id;
            $allProcessesTotalPrice += $proceses[$id]['processTotalPrice'];
            $allProcessTotalPaid += $proceses[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $proceses[$id]['processTotalRemaining'];

            if ($request->ch_detialed == "1") {
                $index = 0;
                $totalWithdrawalValue = 0;
                foreach ($supplierProcess->items as $item) {
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['quantity'] = $item->quantity;
                    $proceses[$id]['processDetails'][$index]['desc'] = $item->description;
                    $index++;
                }
                if ($supplierProcess->has_discount == "1") {
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = $supplierProcess->discountValue();
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم بسبب : " . $supplierProcess->discount_reason;
                    $index++;
                }
                if ($supplierProcess->require_bill == "1") {
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $supplierProcess->taxesValue();
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "قيمة الضريبة المضافة";
                    $index++;
                }
                
                foreach ($supplierProcess->withdrawals()->where('supplier_id', $supplierProcess->supplier->id)->get() as $withdrawal) {
                    $totalWithdrawalValue += $withdrawal->withdrawValue;
                    $proceses[$id]['processDetails'][$index]['date'] = Carbon::parse($withdrawal->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = $supplierProcess->totalPriceAfterTaxes() - $totalWithdrawalValue;
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
        if ($request->ch_detialed == "0") {
            return view("reports.supplier.total", compact('supplierName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        } else {
            return view("reports.supplier.detialed", compact('supplierName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        }
    }

    private function printSupplierPDF($ch_detialed, $withLetterHead, $supplierName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
        if ($ch_detialed == "0") {
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

    public function printSupplierTotalPDF(Request $request) {
        return $this->printSupplierPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    public function printSupplierDetailedPDF(Request $request) {
        return $this->printSupplierPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'),  session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

}
