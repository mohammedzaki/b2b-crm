<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
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
        //$this->middleware('ability:admin,deposit-withdraw-edit');
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
        //$depositWithdraws = DepositWithdraw::select()->whereRaw('Date(created_at) = CURDATE()')->get();
        $depositWithdraws = DepositWithdraw::where('created_at', ">=", Carbon::today())->get();
        $clients_tmp = [];
        $employees_tmp = [];
        $suppliers_tmp = [];
        $expenses_tmp = [];
        $payMethod = [];
        //$depositWithdraws_tmp = [];
        $payMethods = [];
        $payMethods[0] = "كاش";
        $payMethods[1] = "شيك";
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
        $canEdit = 0;
        return view('depositwithdraw', compact(['numbers', 'clients', 'employees', 'suppliers', 'expenses', 'depositWithdraws', 'payMethods', 'canEdit']));
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

        if ($validator->fails()) {
            return response()->json(array(
                        'success' => false,
                        'message' => 'حدث حطأ في حفظ البيانات.',
                        'errors' => $validator->getMessageBag()->toArray()
            ));
        } else {
            $id = DepositWithdraw::create($request->all())->id;
            return response()->json(array(
                        'success' => true,
                        'id' => $id,
                        'message' => 'تم اضافة وارد جديد.',
                        'errors' => $validator->getMessageBag()->toArray()
            ));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function LockSaveToAll(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();

        $rowsIds = [];
        
        foreach ($all['rowsIds'] as $id) {
            DepositWithdraw::where('id', $id)->update(['saveStatus' => 2]);
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                    'success' => true,
                    'rowsIds' => $rowsIds,
                    'message' => 'تم حفظ الوارد.',
                    'errors' => $validator->getMessageBag()->toArray()
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function RemoveSelected(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();

        $rowsIds = [];
        
        foreach ($all['rowsIds'] as $id) {
            DepositWithdraw::where('id', $id)->delete(); //->update(['saveStatus' => 2]);
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                    'success' => true,
                    'rowsIds' => $rowsIds,
                    'message' => 'تم حذف الوارد.',
                    'errors' => $validator->getMessageBag()->toArray()
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
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
        $startDate = Carbon::parse($request['targetdate']);
        $endDate = Carbon::parse($request['targetdate'])->format('Y-m-d 11:59:59');
        $depositWithdraws = DepositWithdraw::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $clients_tmp = [];
        $employees_tmp = [];
        $suppliers_tmp = [];
        $expenses_tmp = [];
        $payMethod = [];
        //$depositWithdraws_tmp = [];
        $payMethods = [];
        $payMethods[0] = "كاش";
        $payMethods[1] = "شيك";
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
        $canEdit = 1;
        return view('depositwithdraw', compact(['numbers', 'clients', 'employees', 'suppliers', 'expenses', 'depositWithdraws', 'payMethods', 'canEdit']));
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
        $depositWithdraw = DepositWithdraw::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $depositWithdraw->id);

        if ($validator->fails()) {
            return response()->json(array(
                        'success' => false,
                        'message' => 'حدث حطأ في حفظ البيانات.',
                        'errors' => $validator->getMessageBag()->toArray()
            ));
        } else {
            $depositWithdraw->update($all);
            return response()->json(array(
                        'success' => true,
                        'id' => $id,
                        'message' => 'تم تعديل وارد جديد.',
                        'errors' => $validator->getMessageBag()->toArray()
            ));
        }
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
