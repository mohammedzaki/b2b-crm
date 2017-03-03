<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Employee;
use App\EmployeeBorrow;
use Validator;

class EmployeeBorrowController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,employees-permissions');
    }

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
        $employees = Employee::select('id', 'name', 'daily_salary')->get();
        $employees_tmp = [];
        $employees_salary_tmp = [];

        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
            $employees_salary_tmp[$employee->id] = $employee->daily_salary;
        }
        $employees = $employees_tmp;
        $employees_salary = $employees_salary_tmp;
        return view('employee.borrow.create', compact(['employees', 'employees_salary']));
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
            /* get employee info */
            $employee = Employee::find($request->employee_id);

            /* Can't create new borrow if employee has payment lower than the borrow */

            $all['is_active'] = 1;
            $employeeBorrow = EmployeeBorrow::create($all);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $borrow = EmployeeBorrow::findOrFail($id);
        $employees = Employee::where('id', $borrow->employee_id)->firstOrFail();
        $employees_tmp[$employees->id] = $employees->name;
        $employees_salary_tmp[$employees->id] = $employees->daily_salary;



        $employees = $employees_tmp;
        $employees_salary = $employees_salary_tmp;

//        return $employees_salary;
        return view('employee.borrow.edit', compact(['borrow', 'employees', 'employees_salary']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $employeeBorrow = EmployeeBorrow::findOrFail($id);
        $validator = $this->validator($request->all());


        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            //$employeeBorrow->id = $request->id;
            $employeeBorrow->employee_id = $request->employee_id;
            $employeeBorrow->amount = $request->amount;
            $employeeBorrow->borrow_reason = $request->borrow_reason;
            $employeeBorrow->pay_amount = $request->pay_amount;
            $employeeBorrow->save();

            return redirect()->back()->with('success', 'تم تعديل بيانات العميل.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        $employee = EmployeeBorrow::where('id', $id)->firstOrFail();
        // dd($employee);
        $employee->delete();


        return redirect()->back()->with('success', 'تم حذف موظف.');
    }

}
