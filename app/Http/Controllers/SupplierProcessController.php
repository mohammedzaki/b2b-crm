<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Supplier;
use App\Employee;
use App\SupplierProcess;
use App\SupplierProcessItem;
use App\Client;
use App\ClientProcess;

class SupplierProcessController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,new-process-supplier');
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'client_id' => 'required|exists:clients,id',
                    'client_process_id' => 'required|exists:client_processes,id',
                    'supplier_id' => 'required|exists:suppliers,id',
                    'employee_id' => 'required|exists:employees,user_id',
                    'notes' => 'string',
                    'has_discount' => 'boolean',
                    'discount_percentage' => 'required_with:has_discount|numeric',
                    'discount_reason' => 'required_with:has_discount|string',
                    'require_bill' => 'boolean',
                    'total_price' => 'required|numeric',
                    'items.*.description' => 'required|string',
                    'items.*.quantity' => 'required|numeric',
                    'items.*.unit_price' => 'required|numeric',
                    'items.*.total_price' => 'numeric'
        ]);

        $validator->setAttributeNames([
            'client_id' => 'اسم العميل',
            'client_process_id' => 'اسم العملية',
            'supplier_id' => 'اسم المورد',
            'employee_id' => 'مشرف العملية',
            'notes' => 'ملاحظات',
            'has_discount' => 'الخصم',
            'discount_percentage' => 'نسبة الخصم',
            'discount_reason' => 'سبب الخصم',
            'require_bill' => 'فاتورة',
            'items.*.description' => 'البيان',
            'items.*.quantity' => 'الكمية',
            'items.*.unit_price' => 'سعر الوحدة',
            'items.*.total_price' => 'القيمة'
        ]);

        return $validator;
    }

    public function index() {
        $processes = SupplierProcess::all();
        return view('supplier.process.index', compact('processes'));
    }

    public function create() {
        $suppliers = Supplier::select('id', 'name')->get();
        $clients = Client::all();
        $employees = Employee::select('user_id', 'name')->get();
        $suppliers_tmp = [];
        $employees_tmp = [];
        $clients_tmp = [];
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->user_id] = $employee->name;
        }
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        $suppliers = $suppliers_tmp;
        $employees = $employees_tmp;
        $clients = $clients_tmp;
        return view('supplier.process.create', compact(['suppliers', 'employees', 'clients']));
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get supplier info */
            $supplier = Supplier::find($request->supplier_id);
            /* get supplier all processes */
            $supplier_processes = $supplier->processes;
            $total_opened_processes_price = 0;

//            foreach ($supplier_processes as $supplier_process) {
//                /* count opened process only */
//                if($supplier_process->status == "active"){
//                    $total_opened_processes_price += $supplier_process->total_price;
//                }
//            }
            /* Can't create new process if supplier has exceeded the credit limit */
            // if($total_opened_processes_price >= $supplier->credit_limit){
//            if($supplier->credit_limit < ($total_opened_processes_price + $request->total_price)){
//                return redirect()->back()->withInput()->with('error',
//                    "خطأ في انشاء عملية جديدة، العميل ".$supplier->name." قد تعدى الحد اﻻئتماني المسموح له."
//                );
//            }else{

            $all['status'] = 'active';
            $supplierProcess = SupplierProcess::create($all);

            foreach ($all['items'] as $item) {
                $item['process_id'] = $supplierProcess->id;
                SupplierProcessItem::create($item);
            }

            return redirect()->route('supplier.process.index')->with('success', 'تم اضافة عملية جديدة.');
//            }
        }
    }

    public function edit($id) {
        $process = SupplierProcess::findOrFail($id);
        $suppliers = Supplier::select('id', 'name')->get();
        $employees = Employee::select('user_id', 'name')->get();
        $suppliers_tmp = [];
        $employees_tmp = [];
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->user_id] = $employee->name;
        }
        $suppliers = $suppliers_tmp;
        $employees = $employees_tmp;
        return view('supplier.process.edit', compact(['process', 'suppliers', 'employees']));
    }

    public function update(Request $request, $id) {
        $process = SupplierProcess::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $process->id);
        $all['items'] = array_values($request->items);

        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get supplier info */
            $supplier = Supplier::find($request->supplier_id);
            /* get supplier all processes */
            $supplier_processes = $supplier->processes;
            $total_opened_processes_price = 0;

//            foreach ($supplier_processes as $supplier_process) {
//                /* count opened process only */
//                if($supplier_process->status == "active" &&
//                    $supplier_process->id != $process->id)
//                {
//                    $total_opened_processes_price += $supplier_process->total_price;
//                }
//            }
            /* Can't create new process if supplier has exceeded the credit limit */
            // if($total_opened_processes_price >= $supplier->credit_limit){
//            if($supplier->credit_limit < ($total_opened_processes_price + $request->total_price)){
//                return redirect()->back()->withInput($all)->with('error',
//                    "خطأ في انشاء عملية جديدة، العميل ".$supplier->name." قد تعدى الحد اﻻئتماني المسموح له."
//                );
//            }else{
            $process->update($all);
            $items_ids = [];

            foreach ($all['items'] as $item) {
                if (isset($item['id'])) {
                    $process_item = $process->items->where('id', intval($item['id']))->first();
                    $items_ids[] = $item['id'];
                    $process_item->update($item);
                } else {
                    $item['process_id'] = $process->id;
                    $pItem = SupplierProcessItem::create($item);
                    $items_ids[] = $pItem->id;
                }
            }
            /* delete others if exists */
            SupplierProcessItem::where('process_id', $process->id)
                    ->whereNotIn('id', $items_ids)->forceDelete();

            return redirect()->route('supplier.process.index')->with('success', 'تم تعديل بيانات عملية.');
//            }
        }
    }

    public function destroy($id) {
        $process = SupplierProcess::findOrFail($id);
        foreach ($process->items as $item) {
            $item->delete();
        }
        $process->delete();
        return redirect()->back()->with('success', 'تم حذف العملية.');
    }

    public function trash() {
        $processes = SupplierProcess::onlyTrashed()->get();
        return view('supplier.process.trash', compact('processes'));
    }

    public function restore($id) {
        SupplierProcess::withTrashed()->find($id)->restore();
        SupplierProcessItem::withTrashed()->where('process_id', $id)->restore();
        return redirect()->route('supplier.process.index')->with('success', 'تم استرجاع العملية.');
    }

    public function getClientProcesses(Request $request) {
        $input = $request->input('option');
        $clientProcesses = ClientProcess::where('client_id', $input)->get();
        $clientProcesses_tmp = [];
        foreach ($clientProcesses as $process) {
            $clientProcesses_tmp[$process->id] = $process->name;
        }
        $clientProcesses = $clientProcesses_tmp;
        return response()->json($clientProcesses);
    }

    public function getSupplierProcesses(Request $request) {
        $input = $request->input('option');
        $clientProcesses = SupplierProcess::where('supplier_id', $input)->get();
        $clientProcesses_tmp = [];
        foreach ($clientProcesses as $process) {
            $clientProcesses_tmp[$process->id] = $process->name;
        }
        $clientProcesses = $clientProcesses_tmp;
        return response()->json($clientProcesses);
    }

}
