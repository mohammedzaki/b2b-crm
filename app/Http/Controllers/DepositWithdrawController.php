<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Client;
use App\Supplier;
use App\ClientProcess;
use App\Employee;
use App\Expenses;
use App\SupplierProcess;
use App\DepositWithdraw;
use App\Facility;
use Validator;

class DepositWithdrawController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
	$this->middleware('ability:admin,deposit-withdraw');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $numbers['clients_number'] = Client::count();
        $numbers['suppliers_number'] = Supplier::count();
        $numbers['process_number'] = ClientProcess::count();
        $numbers['Supplierprocess_number'] = SupplierProcess::count();
        // TODO: must re-program
        $numbers['current_amount'] = ((DepositWithdraw::sum('depositValue') + Facility::sum('opening_amount')) - DepositWithdraw::sum('withdrawValue'));

        $clients = Client::select('id', 'name')->get();
        $employees = Employee::select('user_id', 'name')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $expenses = Expenses::all();
        $depositWithdraws = DepositWithdraw::all();
        $clients_tmp = [];
        $employees_tmp = [];
        $suppliers_tmp = [];
        $expenses_tmp = [];
        //$depositWithdraws_tmp = [];
        
        //$clients_tmp[-1] = '';
        //$employees_tmp[-1] = '';
        //$suppliers_tmp[-1] = '';
        //$expenses_tmp[-1] = '';
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->user_id] = $employee->name;
        }
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($expenses as $expense) {
            $expenses_tmp[$expense->id] = $expense->name;
        }
        $clients = $clients_tmp;
        $employees = $employees_tmp;
        $suppliers = $suppliers_tmp;
        $expenses = $expenses_tmp;
        return view('depositwithdraw', compact(['numbers', 'clients', 'employees', 'suppliers', 'expenses', 'depositWithdraws']));
    }

    
    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'depositValue' => 'numeric',
                    'withdrawValue' => 'numeric',
                    'recordDesc' => 'required|string',
                    'cbo_processes' => 'numeric',
                    'client_id' => 'exists:clients,id',
                    'employee_id' => 'exists:employees,user_id',
                    'supplier_id' => 'exists:suppliers,id',
                    'expenses_id' => 'exists:expenses,id',
                    'payMethod' => 'required|numeric',
                    'notes' => 'string'
        ]);

        $validator->setAttributeNames([
            'depositValue' => 'قيمة الوارد',
            'withdrawValue' => 'قيمة المنصرف',
            'recordDesc' => 'البيان',
            'cbo_processes' => 'اسم العملية',
            'client_id' => 'اسم العميل',
            'employee_id' => 'مشرف العملية',
            'expenses_id' => 'اسم المصروف',
            'payMethod' => 'طريقة الدفع',
            'supplier_id' => 'اسم المورد',
            'notes' => 'مﻻحظات'
        ]);

        return $validator;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = $this->validator($request->all());
        //$all = $request->all();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            
            DepositWithdraw::create($request->all());
            return redirect()->route('dashboard.index')->with('success', 'تم اضافة وارد جديد.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
