<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Reports\Supplier\SupplierAnalyticalCenterDetailed;
use App\Reports\Supplier\SupplierAnalyticalCenterTotal;
use Illuminate\Http\Request;
use Validator;

/**
 * Description of SupplierAnalyticalCenterController
 *
 * @author mohammedzaki
 */
class SupplierAnalyticalCenterController extends Controller {
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
        $suppliers = Supplier::allHasOpenProcess();
        $suppliers_tmp = [];
        $index = 0;
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$index]['id'] = $supplier->id;
            $suppliers_tmp[$index]['name'] = $supplier->name;
            $suppliers_tmp[$index]['totalDeal'] = $supplier->getTotalDeal();
            $suppliers_tmp[$index]['totalPaid'] = $supplier->getTotalPaid();
            $suppliers_tmp[$index]['totalRemaining'] = $supplier->getTotalRemaining();
            $index++;
        }
        $suppliers = $suppliers_tmp;

        return view('reports.SupplierAnalyticalCenter.index', compact("suppliers"));
    }

    public function viewReport(Request $request) {
        //{"ch_detialed":"0","supplier_id":"1","processes":["1","2"]}
        $supplierName = "";
        $allSuppliersTotalPrice = 0;
        $allSuppliersTotalPaid = 0;
        $allSuppliersTotalRemaining = 0;

        $suppliers = [];
        foreach ($request->selectedIds as $id) {
            $supplier = Supplier::findOrFail($id);
            $suppliers[$id]['supplierName'] = $supplier->name;
            $suppliers[$id]['supplierNum'] = $supplier->id;
            
            $suppliers[$id]['supplierTotalPrice'] = $supplier->getTotalDeal();
            $suppliers[$id]['supplierTotalPaid'] = $supplier->getTotalPaid();
            $suppliers[$id]['supplierTotalRemaining'] = $supplier->getTotalRemaining();
            
            $allSuppliersTotalPrice += $suppliers[$id]['supplierTotalPrice'];
            $allSuppliersTotalPaid += $suppliers[$id]['supplierTotalPaid'];
            $allSuppliersTotalRemaining += $suppliers[$id]['supplierTotalRemaining'];

            if ($request->ch_detialed == TRUE) {
                $index = 0;
                $suppliers[$id]['processDetails'] = [];
                foreach ($supplier->processes as $process) {
                    $suppliers[$id]['processDetails'][$index]['name'] = $process->name;
                    $suppliers[$id]['processDetails'][$index]['totalPrice'] = $process->total_price_taxes;
                    $suppliers[$id]['processDetails'][$index]['paid'] = $process->totalWithdrawals();
                    $suppliers[$id]['processDetails'][$index]['remaining'] = $process->total_price_taxes - $process->totalWithdrawals();
                    $suppliers[$id]['processDetails'][$index]['date'] = $process->created_at;
                    $index++;
                }
            }
        }
        
        session([
            'supplierName' => "",
            'suppliers' => $suppliers,
            'allSuppliersTotalPrice' => $allSuppliersTotalPrice,
            'allSuppliersTotalPaid' => $allSuppliersTotalPaid,
            'allSuppliersTotalRemaining' => $allSuppliersTotalRemaining
        ]);
        if ($request->ch_detialed == FALSE) {
            return view("reports.SupplierAnalyticalCenter.total", compact('supplierName', 'suppliers', 'allSuppliersTotalPrice', 'allSuppliersTotalPaid', 'allSuppliersTotalRemaining'));
        } else {
            return view("reports.SupplierAnalyticalCenter.detialed", compact('supplierName', 'suppliers', 'allSuppliersTotalPrice', 'allSuppliersTotalPaid', 'allSuppliersTotalRemaining'));
        }
    }

    public function printTotalPDF(Request $request) {
        return $this->printSupplierPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('suppliers'), session('allSuppliersTotalPrice'), session('allSuppliersTotalPaid'), session('allSuppliersTotalRemaining'));
    }

    public function printDetailedPDF(Request $request) {
        return $this->printSupplierPDF($request->ch_detialed, $request->withLetterHead, session('supplierName'), session('suppliers'), session('allSuppliersTotalPrice'), session('allSuppliersTotalPaid'), session('allSuppliersTotalRemaining'));
    }

    private function printSupplierPDF($ch_detialed, $withLetterHead, $supplierName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
        if ($ch_detialed == FALSE) {
            $pdfReport = new SupplierAnalyticalCenterTotal($withLetterHead);
        } else {
            $pdfReport = new SupplierAnalyticalCenterDetailed($withLetterHead);
        }
        $pdfReport->supplierName = $supplierName;
        $pdfReport->proceses = $proceses;
        $pdfReport->allProcessesTotalPrice = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->RenderReport();
    }
}
