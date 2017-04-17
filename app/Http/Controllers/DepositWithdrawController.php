<?php

namespace App\Http\Controllers;

use App\Constants\EmployeeActions;
use App\Constants\PaymentMethods;
use App\Extensions\DateTime;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\Expenses;
use App\Models\OpeningAmount;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
     * @return Response
     */
    public function index() {
        $startDate = DateTime::today()->startOfDay();
        $endDate = DateTime::today()->endOfDay();
        return $this->getDepositWithdrawsItems($startDate, $endDate, 0);
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'depositValue' => 'numeric',
                    'withdrawValue' => 'numeric',
                    'recordDesc' => 'required|string',
                    'cbo_processes' => 'numeric',
                    'client_id' => 'exists:clients,id',
                    'employee_id' => 'exists:employees,id',
                    'supplier_id' => 'exists:suppliers,id',
                    //'expenses_id' => 'exists:expenses,id',
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
            'notes' => 'ملاحظات'
        ]);

        return $validator;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        //$validator = $this->validator($request->all());

        /* if ($validator->fails()) {
          return response()->json(array(
          'success' => false,
          'message' => 'حدث حطأ في حفظ البيانات.',
          'errors' => $validator->getMessageBag()->toArray()
          ));
          } else { */

        // FIXME: رد عهدة مرتين
        // FIXME: save with expense_id = null and value of deposit = 0
        if (isset($request->employee_id) && $request->expenses_id == EmployeeActions::Guardianship) {

            $employee = Employee::findOrFail($request->employee_id);

            $lG = $employee->lastGuardianship();
            $lGR = $employee->lastGuardianshipReturn();
            $lGId = $employee->lastGuardianshipId();
            $lGRId = $employee->lastGuardianshipReturnId();

            if (($lG != $lGR) || ($lGId > $lGRId)) {
                return response()->json(array(
                            'success' => false,
                            'message' => 'error $employee->lastGuardianship() != $employee->lastGuardianshipReturn()',
                                //'errors' => $validator->getMessageBag()->toArray()
                ));
            }
        } else if (isset($request->employee_id) && $request->expenses_id == EmployeeActions::GuardianshipReturn) {
            $employee = Employee::findOrFail($request->employee_id);
            if ($employee->lastGuardianship() != $request->depositValue) {
                return response()->json(array(
                            'success' => false,
                            'message' => 'error $employee->lastGuardianship() != $request->depositValue',
                                //'errors' => $validator->getMessageBag()->toArray()
                ));
            }
        }
        $all = $request->all();
        $all['due_date'] = DateTime::parse($request->due_date);
        $depositWithdraw = DepositWithdraw::create($all);
        $this->CheckProcessClosed($depositWithdraw);
        return response()->json(array(
                    'success' => true,
                    'id' => $depositWithdraw->id,
                    'current_amount' => $this->CalculateCurrentAmount(),
                    'message' => 'تم اضافة وارد جديد.',
                        //'errors' => $validator->getMessageBag()->toArray()
        ));
        //}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
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
                    'current_amount' => $this->CalculateCurrentAmount(),
                    'message' => 'تم حفظ الوارد.',
                        //'errors' => $validator->getMessageBag()->toArray()
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function RemoveSelected(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();

        $rowsIds = [];

        foreach ($all['rowsIds'] as $id) {
            $depositWithdraw = DepositWithdraw::findOrFail($id);
            $depositWithdraw->depositValue = 0;
            $depositWithdraw->withdrawValue = 0;
            $depositWithdraw->save();
            $this->CheckProcessClosed($depositWithdraw);
            DepositWithdraw::where('id', $id)->delete();
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                    'success' => true,
                    'rowsIds' => $rowsIds,
                    'current_amount' => $this->CalculateCurrentAmount(),
                    'message' => 'تم حذف الوارد.',
                        //'errors' => $validator->getMessageBag()->toArray()
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function search(Request $request) {
        $user = Auth::user();
        if (!$user->ability('admin', 'deposit-withdraw-edit')) {
            return response()->view('errors.403', [], 403);
        }
        $startDate = DateTime::parse($request['targetdate'])->startOfDay();
        $endDate = DateTime::parse($request['targetdate'])->endOfDay();
        return $this->getDepositWithdrawsItems($startDate, $endDate, 1, TRUE);
    }

    private function getDepositWithdrawsItems(DateTime $startDate, DateTime $endDate, $canEdit, $isSearch = FALSE) {
        $numbers['clients_number'] = Client::count();
        $numbers['suppliers_number'] = Supplier::count();
        $numbers['process_number'] = ClientProcess::count();
        $numbers['Supplierprocess_number'] = SupplierProcess::count();

        $numbers['withdraws_amount'] = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->sum('withdrawValue');
        $numbers['deposits_amount'] = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->sum('depositValue');

        $numbers['current_dayOfWeek'] = $startDate->dayOfWeek;
        $numbers['current_dayOfMonth'] = $startDate->day;
        $numbers['current_month'] = $startDate->month - 1;
        $numbers['current_year'] = $startDate->year;
        $employees = Employee::select('id', 'name')->get();
        $expenses = Expenses::all();
        $depositWithdraws = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->get();

        if ($isSearch) {
            $clients = Client::all();
            $suppliers = Supplier::all();
            $clientProcesses = ClientProcess::all();
            $supplierProcesses = SupplierProcess::all();
            $numbers['current_amount'] = $this->CalculateCurrentAmountOff($startDate, $endDate);
            $numbers['currentDay_amountOff'] = $this->CalculateCurrentAmountOff($startDate->addDay(-1), $endDate->addDay(-1));
        } else {
            $clients = Client::allHasOpenProcess();
            $suppliers = Supplier::allHasOpenProcess();
            $clientProcesses = ClientProcess::allOpened()->get();
            $supplierProcesses = SupplierProcess::allOpened()->get();
            $numbers['current_amount'] = $this->CalculateCurrentAmount();
            $numbers['currentDay_amountOff'] = $this->CalculateCurrentAmountOff($startDate->addDay(-1), $endDate->addDay(-1));
        }

        $clients_tmp = [];
        $employees_tmp = [];
        $suppliers_tmp = [];
        $expenses_tmp = [];
        $clientProcesses_tmp = [];
        $supplierProcesses_tmp = [];
        $payMethods = PaymentMethods::all();
        $employeeActions = EmployeeActions::all();

        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($expenses as $expense) {
            $expenses_tmp[$expense->id] = $expense->name;
        }
        foreach ($clientProcesses as $process) {
            $clientProcesses_tmp[$process->id]['name'] = $process->name;
            $clientProcesses_tmp[$process->id]['client_id'] = $process->client->id;
        }
        foreach ($supplierProcesses as $process) {
            $supplierProcesses_tmp[$process->id]['name'] = $process->name;
            $supplierProcesses_tmp[$process->id]['supplier_id'] = $process->supplier->id;
        }
        $clientProcesses = $clientProcesses_tmp;
        $supplierProcesses = $supplierProcesses_tmp;

        $clients = $clients_tmp;
        $employees = $employees_tmp;
        $suppliers = $suppliers_tmp;
        $expenses = $expenses_tmp;
        $canEdit = $canEdit;
        return view('depositwithdraw.index', compact(['numbers', 'clients', 'employees', 'suppliers', 'expenses', 'depositWithdraws', 'payMethods', 'canEdit', 'clientProcesses', 'supplierProcesses', 'employeeActions', 'isSearch']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $depositWithdraw = DepositWithdraw::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $depositWithdraw->id);

        /* if ($validator->fails()) {
          return response()->json(array(
          'success' => false,
          'message' => 'حدث حطأ في حفظ البيانات.',
          'errors' => $validator->getMessageBag()->toArray()
          ));
          } else { */

        $all['due_date'] = DateTime::parse($request->due_date);
        $depositWithdraw->update($all);
        $this->CheckProcessClosed($depositWithdraw);
        return response()->json(array(
                    'success' => true,
                    'id' => $depositWithdraw->id,
                    '$depositWithdraw->cbo_processe' => $depositWithdraw->cbo_processes,
                    'current_amount' => $this->CalculateCurrentAmount(),
                    'message' => 'تم تعديل وارد جديد.',
                        //'errors' => $validator->getMessageBag()->toArray()
        ));
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    private function CalculateCurrentAmount() {
        $startDate = DateTime::today()->format('2017-01-01 00:00:00');
        $endDate = DateTime::today()->format('2017-12-31 00:00:00');
        $depositValue = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->sum('depositValue');
        $withdrawValue = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->sum('withdrawValue');
        $openingAmount = OpeningAmount::whereBetween('deposit_date', [$startDate, $endDate])->sum('amount');
        return round(($depositValue + $openingAmount) - $withdrawValue, 2);
    }

    private function CalculateCurrentAmountOff($startDate, $endDate) {
        $startDate = DateTime::today()->format('2017-01-01 00:00:00');
        $depositValue = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->sum('depositValue');
        $withdrawValue = DepositWithdraw::whereBetween('due_date', [$startDate, $endDate])->sum('withdrawValue');
        $openingAmount = OpeningAmount::whereBetween('deposit_date', [$startDate, $endDate])->sum('amount');
        return round(($depositValue + $openingAmount) - $withdrawValue, 2);
    }

    private function CheckProcessClosed(DepositWithdraw $depositWithdraw) {
        if (!empty($depositWithdraw->cbo_processes)) {
            if (!empty($depositWithdraw->client_id)) {
                $process = ClientProcess::findOrFail($depositWithdraw->cbo_processes);
                $process->CheckProcessMustClosed();
            } else if (!empty($depositWithdraw->supplier_id)) {
                $process = SupplierProcess::findOrFail($depositWithdraw->cbo_processes);
                $process->CheckProcessMustClosed();
            }
        }
    }

}
