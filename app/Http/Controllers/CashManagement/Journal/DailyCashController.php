<?php

/*
 * B2B CRM Software License
 *
 * Copyright (C) ZakiSoft ltd - All Rights Reserved.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Mohammed Zaki mohammedzaki.dev@gmail.com, September 2017
 */

namespace App\Http\Controllers\CashManagement\Journal;

use App\Constants\EmployeeActions;
use App\Constants\PaymentMethods;
use App\Exceptions\ValidationException;
use App\Extensions\DateTime;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\EmployeeBorrowBilling;
use App\Models\Expenses;
use App\Models\Loans;
use App\Models\OpeningAmount;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

/**
 * Description of DailyCashController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="/depositwithdraw")
 * @Resource("depositwithdraw")
 * @Middleware({"web", "auth", "ability:admin,deposit-withdraw"})
 */
class DailyCashController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getDepositWithdrawsItems($startDate, $endDate, 0);
    }

    private function getDepositWithdrawsItems(DateTime $startDate, DateTime $endDate, $canEdit)
    {
        $depositWithdrawsItems             = DepositWithdraw::getDWItems($startDate, $endDate);
        $numbers['clients_number']         = Client::count();
        $numbers['suppliers_number']       = Supplier::count();
        $numbers['process_number']         = ClientProcess::count();
        $numbers['Supplierprocess_number'] = SupplierProcess::count();
        $numbers['current_dayOfWeek']      = $startDate->dayOfWeek;
        $numbers['current_dayOfMonth']     = $startDate->day;
        $numbers['current_month']          = $startDate->month - 1;
        $numbers['current_year']           = $startDate->year;
        $numbers['withdrawsAmount']        = $depositWithdrawsItems->sum('withdrawValue');
        $numbers['depositsAmount']         = $depositWithdrawsItems->sum('depositValue');
        $numbers['currentAmount']          = DepositWithdraw::calculateCurrentAmount($endDate);
        $numbers['previousDayAmount']      = DepositWithdraw::calculateCurrentAmount($endDate->addDay(-1));

        return view('cash.journal.daily-cash')->with([
                                                         'numbers'          => $numbers,
                                                         'clients'          => Client::allAsList(),
                                                         'employees'        => Employee::allAsList(),
                                                         'suppliers'        => Supplier::allAsList(),
                                                         'expenses'         => Expenses::allAsList(),
                                                         'loans'            => Loans::allAsList(),
                                                         'depositWithdraws' => $depositWithdrawsItems,
                                                         'payMethods'       => PaymentMethods::all(),
                                                         'canEdit'          => $canEdit ? 1 : 0,
                                                         'employeeActions'  => collect(EmployeeActions::all())->toJson()
                                                     ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        DB::beginTransaction();
        $request['due_date'] = DateTime::parse($request->due_date);
        //dd($request->all());
        $depositWithdraw = DepositWithdraw::create();
        if (isset($request->employee_id)) {
            $this->checkEmployeeAction($request, $depositWithdraw->id);
        }
        $request['saveStatus'] = 1;
        $depositWithdraw->update($request->all());
        $this->checkProcessClosed($depositWithdraw);
        DB::commit();
        return response()->json([
                                    'success'       => true,
                                    'id'            => $depositWithdraw->id,
                                    'currentAmount' => DepositWithdraw::calculateCurrentAmount(),
                                    'message'       => 'تم اضافة وارد جديد.'
                                ]);
    }

    function checkEmployeeAction(Request &$request, $id, $is_update = FALSE)
    {
        $employee = Employee::findOrFail($request->employee_id);
        if (!isset($request->expenses_id)) {
            throw new ValidationException('يجب اختيار اسم المصروف');
        }
        switch ($request->expenses_id) {
            case EmployeeActions::FinancialCustody:
                $this->setFinancialCustody($employee, $request);
                break;
            case EmployeeActions::PayLongBorrow:
                $this->payLongBorrow($employee, $request->depositValue, DateTime::parse($request->due_date), $id, $is_update);
                break;
            case EmployeeActions::SmallBorrow:
                break;
            default:
                DB::rollBack();
                throw new ValidationException('يجب اختيار اسم المصروف');
        }
    }

    function setFinancialCustody(Employee $employee, Request &$request)
    {
        $currentFinancialCustody = $employee->currentFinancialCustody();
        $date                    = DateTime::parse($request->due_date);
        if ($currentFinancialCustody == null) {
            $monthName               = $date->getMonthName();
            $currentFinancialCustody = [
                'user_id'     => $request->user_id,
                'description' => "عهدة شراء شهر {$monthName} ",
                'notes'       => '',
                'approved_by' => null,
                'approved_at' => null,
                'due_date'    => $request->due_date
            ];
            $currentFinancialCustody = $employee->financialCustodies()->create($currentFinancialCustody);
        }
        if ($date->month == DateTime::parse($currentFinancialCustody->due_date)->month) {
            $request['financial_custody_id'] = $currentFinancialCustody->id;
        } else {
            DB::rollBack();
            throw new ValidationException('يجب تسوية العهدة الحالية');
        }
    }

    function payLongBorrow(Employee $employee, $depositValue, $due_date, $id, $is_update)
    {
        if ($is_update) {
            $this->resetDiscountBorrows($id);
        }
        if ($employee->hasUnpaidBorrow()) {
            if ($employee->totalUnpaidBorrow() >= $depositValue) {
                $this->discountBorrows($employee, $depositValue, $due_date, $id);
            } else {
                DB::rollBack();
                throw new ValidationException("القيمة المردودة اكبر من اجمالى الدفعات المتبقية {$employee->totalUnpaidBorrow()}");
            }
        } else {
            DB::rollBack();
            throw new ValidationException('هذا الموظف ليس له دفعات متبقية');
        }
    }

    function resetDiscountBorrows($depositWithdraw)
    {
        foreach ($depositWithdraw->employeeLogBorrowBillings as $borrow) {
            $borrow->paying_status = EmployeeBorrowBilling::UN_PAID;
            $borrow->paid_amount   = null;
            $borrow->paid_date     = null;
            $borrow->deposit_id    = null;
            $borrow->save();
        }
    }

    function discountBorrows(Employee $employee, $depositValue, $due_date, $id)
    {
        foreach ($employee->unpaidBorrows() as $borrow) {
            if ($depositValue >= $borrow->pay_amount) {
                $depositValue          -= $borrow->pay_amount;
                $borrow->paying_status = EmployeeBorrowBilling::PAID;
                $borrow->paid_amount   = $borrow->pay_amount;
                $borrow->paid_date     = $due_date;
                $borrow->deposit_id    = $id;
                $borrow->save();
            } else if ($depositValue == 0) {
                break;
            } else {
                //$borrow->pay_amount -= $depositValue;
                $borrow->paid_amount = $depositValue;
                $borrow->paid_date   = $due_date;
                $borrow->deposit_id  = $id;
                $borrow->save();
                break;
            }
        }
    }

    private function checkProcessClosed(DepositWithdraw $depositWithdraw)
    {
        if (!empty($depositWithdraw->cbo_processes)) {
            if (!empty($depositWithdraw->client_id)) {
                $process = ClientProcess::findOrFail($depositWithdraw->cbo_processes);
                $process->checkProcessMustClosed();
            } else if (!empty($depositWithdraw->supplier_id)) {
                $process = SupplierProcess::findOrFail($depositWithdraw->cbo_processes);
                $process->checkProcessMustClosed();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/lockSaveAll", as="depositwithdraw.lockSaveAll")
     */
    public function lockSaveAll(Request $request)
    {
        $all = $request->all();

        $rowsIds = [];

        foreach ($all['rowsIds'] as $id) {
            DepositWithdraw::where('id', $id)->update(['saveStatus' => 2]);
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                                    'success'       => true,
                                    'rowsIds'       => $rowsIds,
                                    'currentAmount' => DepositWithdraw::calculateCurrentAmount(),
                                    'message'       => 'تم حفظ الوارد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/removeSelected", as="depositwithdraw.removeSelected")
     */
    public function removeSelected(Request $request)
    {
        $validator = $this->validator($request->all());
        $all       = $request->all();

        $rowsIds = [];

        foreach ($all['rowsIds'] as $id) {
            $depositWithdraw = DepositWithdraw::findOrFail($id);
            DepositWithdraw::where('id', $id)->first()->delete();
            $this->checkProcessClosed($depositWithdraw);
            $this->resetDiscountBorrows($depositWithdraw);
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                                    'success'       => true,
                                    'rowsIds'       => $rowsIds,
                                    'currentAmount' => DepositWithdraw::calculateCurrentAmount(),
                                    'message'       => 'تم حذف الوارد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

    protected function validator(array $data, $id = null)
    {
        $validator = Validator::make($data, [
            /* 'depositValue' => 'numeric',
              'withdrawValue' => 'numeric',
              'recordDesc' => 'required|string',
              'cbo_processes' => 'numeric',
              'client_id' => 'exists:clients,id',
              'employee_id' => 'exists:employees,id',
              'supplier_id' => 'exists:suppliers,id',
              //'expenses_id' => 'exists:expenses,id',
              'payMethod' => 'required|numeric',
              'notes' => 'string' */
        ]);

        $validator->setAttributeNames([
                                          /* 'depositValue' => 'قيمة الوارد',
                                            'withdrawValue' => 'قيمة المنصرف',
                                            'recordDesc' => 'البيان',
                                            'cbo_processes' => 'اسم العملية',
                                            'client_id' => 'اسم العميل',
                                            'employee_id' => 'مشرف العملية',
                                            'expenses_id' => 'اسم المصروف',
                                            'payMethod' => 'طريقة الدفع',
                                            'supplier_id' => 'اسم المورد',
                                            'notes' => 'ملاحظات' */
                                      ]);

        return $validator;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     * @Get("/search", as="depositwithdraw.search")
     * @Middleware({"ability:admin,deposit-withdraw-edit"})
     */
    public function search(Request $request)
    {
        $startDate = DateTime::parse($request['targetdate'])->startOfDay();
        $endDate   = DateTime::parse($request['targetdate'])->endOfDay();
        return $this->getDepositWithdrawsItems($startDate, $endDate, auth()->user()->hasRole('admin'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $request->user_id = auth()->user()->id;
        $depositWithdraw  = DepositWithdraw::findOrFail($id);
        if (isset($request->employee_id)) {
            $this->checkEmployeeAction($request, $depositWithdraw->id, TRUE);
        }
        unset($request['due_date']); // to prevent changing the date
        $depositWithdraw->update($request->all());
        $this->checkProcessClosed($depositWithdraw);
        DB::commit();
        return response()->json(array(
                                    'success'       => true,
                                    'id'            => $depositWithdraw->id,
                                    //'$depositWithdraw->cbo_processe' => $depositWithdraw->cbo_processes,
                                    'currentAmount' => DepositWithdraw::calculateCurrentAmount(),
                                    'message'       => 'تم تعديل وارد جديد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        // test
    }

}
