<?php

namespace App\Http\Controllers;

use App\Constants\EmployeeActions;
use App\Constants\PaymentMethods;
use App\Extensions\DateTime;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\EmployeeBorrow;
use App\Models\EmployeeBorrowBilling;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="employeeBorrow")
 * @Resource("employeeBorrow")
 * @Middleware({"web", "auth", "ability:admin,employees-permissions"})
 */
class EmployeeBorrowController extends Controller {

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'employee_id' => 'required|exists:employees,id',
                    'borrow_reason' => 'required_with:has_discount|string',
                    'amount' => 'required|numeric',
                    'pay_percentage' => 'numeric',
                    'pay_amount' => 'numeric'
        ]);

        $validator->setAttributeNames([
            'employee_id' => 'أسم الموظف',
            'borrow_reason' => 'سبب السلفية',
            'amount' => 'القيمة',
            'pay_percentage' => 'نسبة الخصم',
            'pay_amount' => 'قيمة الخصم'
        ]);

        return $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $employeeBorrows = EmployeeBorrow::all();
        return view('employee.borrow.index', compact('employeeBorrows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $employees = Employee::select('id', 'name', 'daily_salary')->where("borrow_system", 1)->get();
        $employees_tmp = [];
        $employeesSalaries = [];
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
            $employeesSalaries[$employee->id]['dailySalary'] = $employee->daily_salary;
        }
        $employees = $employees_tmp;
        return view('employee.borrow.create', compact(['employees', 'employeesSalaries']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $employee = Employee::find($request->employee_id);
            if ($employee->hasUnpaidBorrow()) {
                return redirect()->back()->withInput()->with('error', 'يجب سداد باقى الدفعات المستحقة اولا');
            }
            $all['is_active'] = TRUE;
            $employeeBorrow = EmployeeBorrow::create($all);
            $depositWithdraw = new DepositWithdraw();
            $depositWithdraw->withdrawValue = $employeeBorrow->amount;
            $depositWithdraw->due_date = DateTime::now();
            $depositWithdraw->recordDesc = "سلفة مستديمة شهر {$depositWithdraw->due_date->month} سنة {$depositWithdraw->due_date->year}";
            $depositWithdraw->employee_id = $employee->id;
            $depositWithdraw->expenses_id = EmployeeActions::LongBorrow;
            $depositWithdraw->payMethod = PaymentMethods::CASH;
            $depositWithdraw->notes = DateTime::now();
            $depositWithdraw->save();

            $times = $employeeBorrow->amount / $employeeBorrow->pay_amount;
            $times = intval($times) + 1;
            $stratDate = DateTime::now();
            if (!$request->start_discount) {
                $stratDate->addMonth(1);
            }
            for ($index = 1; $index <= $times; $index++) {
                if ($index == $times) {
                    $value = $employeeBorrow->amount % $employeeBorrow->pay_amount;
                    if ($value > 0) {
                        $em = new EmployeeBorrowBilling;
                        $em->pay_amount = $value;
                        $em->due_date = $stratDate;
                        $em->paying_status = EmployeeBorrowBilling::UN_PAID;
                        $em->employee_borrow_id = $employeeBorrow->id;
                        $em->save();
                    }
                } else {
                    $em = new EmployeeBorrowBilling;
                    $em->pay_amount = $employeeBorrow->pay_amount;
                    $em->due_date = $stratDate;
                    $em->paying_status = EmployeeBorrowBilling::UN_PAID;
                    $em->employee_borrow_id = $employeeBorrow->id;
                    $em->save();
                }
                $stratDate->addMonth(1);
            }
            return redirect()->route('employeeBorrow.index')->with('success', 'تم اضافة سلفية جديدة.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeBorrow $employeeBorrow) {
        $borrow = $employeeBorrow;
        $employee = Employee::where('id', $borrow->employee_id)->firstOrFail();
        $employees_tmp[$employee->id] = $employee->name;
        $employeesSalaries[$employee->id]['dailySalary'] = $employee->daily_salary;
        $employees = $employees_tmp;
        return view('employee.borrow.edit', compact(['borrow', 'employees', 'employeesSalaries']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeBorrow $employeeBorrow) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $employeeBorrow->employee_id = $request->employee_id;
            $employeeBorrow->amount = $request->amount;
            $employeeBorrow->borrow_reason = $request->borrow_reason;
            $employeeBorrow->pay_amount = $request->pay_amount;
            $employeeBorrow->save();
            return redirect()->back()->with('success', 'تم تعديل بيانات السلفة.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeBorrow $borrow) {
        $borrow->delete();
        return redirect()->back()->with('success', 'تم حذف موظف.');
    }

}
