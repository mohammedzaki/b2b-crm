<?php

/*
 * B2B CRM Software License
 *
 * Copyright (C) ZakiSoft ltd - All Rights Reserved.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Mohammed Zaki mohammedzaki.dev@gmail.com, December 2017
 */

namespace App\Http\Controllers\HR\PayrollManagement;

use App\Constants\EmployeeActions;
use App\Constants\PaymentMethods;
use App\Extensions\DateTime;
use App\Helpers\Helpers;
use App\Models\Attendance;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\EmployeeBorrowBilling;
use App\Models\FinancialCustody;
use App\Reports\Employee\Salary;
use Illuminate\Http\Request;
use DB;
use Exception;
use Debugbar;

/**
 * Description of SalaryController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="salary")
 * @Middleware({"web", "auth"})
 */
class SalaryController
{

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     * @Get("/", as="salary.index")
     */
    public function index(Request $request)
    {
        return view('salary.index')->with($this->getBasicData());
    }

    private function getBasicData($employee_id = 0, $date = '')
    {
        $employees = Employee::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        });
        return [
            'employees'   => $employees,
            'employee_id' => $employee_id,
            'date'        => $date
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  Employee $employee
     * @return \Illuminate\Http\Response
     * @Get("/{employee}", as="salary.show")
     */
    public function show(Request $request, Employee $employee)
    {
        $data = array_merge($this->getBasicData($employee->id, $request->date), $this->getData($employee, DateTime::parse($request->date)));
        return view('salary.show')->with($data);
    }

    private function getData(Employee $employee, DateTime $date)
    {
        $employeeName = $employee->name;
        $monthNum     = $date->month;
        $hourlyRate   = $employee->salaryPerHour();
        $attendances  = Attendance::where([
                                              ['employee_id', '=', $employee->id]
                                          ])
                                  ->whereYear('date', $date->year)
                                  ->whereMonth('date', $date->month)
                                  ->orderBy('date', 'asc')->get()
                                  ->mapWithKeys(function ($attendance) {
                                      return [
                                          $attendance->id => [
                                              'id'                         => $attendance->id,
                                              'processName'                => $attendance->process ? $attendance->process->name : 'عمليات ادارية',
                                              'employeeName'               => $attendance->employee->name,
                                              'workingHours'               => $attendance->workingHoursToString(),
                                              'date'                       => DateTime::parse($attendance->date)->format('l, d-m-Y'),
                                              'borrowValue'                => $attendance->employeeSmallBorrow(),
                                              'absentTypeName'             => $attendance->absentType ? $attendance->absentType->name : null,
                                              'totalWorkingHoursInSeconds' => $attendance->workingHoursToSeconds(),
                                              'salary_deduction'           => $attendance->salary_deduction,
                                              'absent_deduction'           => $attendance->absent_deduction,
                                              'mokaf'                      => $attendance->mokaf,
                                              'date'                       => $attendance->date,
                                              'shift'                      => $attendance->shift,
                                              'check_in'                   => $attendance->check_in,
                                              'check_out'                  => $attendance->check_out,
                                              'notes'                      => $attendance->notes,
                                              'employee_id'                => $attendance->employee_id,
                                              'process_id'                 => $attendance->process_id
                                          ]
                                      ];
                                  });
        $attendances instanceof \Illuminate\Support\Collection;
        $employeeFinancialCustodys = FinancialCustody::where([
                                                                 ['employee_id', '=', $employee->id]
                                                             ])
                                                     ->whereYear('due_date', $date->year)
                                                     ->whereMonth('due_date', $date->month)->get();

        $totalSalaryDeduction             = $attendances->sum('salary_deduction');
        $totalAbsentDeduction             = $attendances->sum('absent_deduction');
        $totalBonuses                     = $attendances->sum('mokaf');
        $totalFinancialCustodyValue       = $employeeFinancialCustodys->sum(function (FinancialCustody $fin) {
            return $fin->totalDeposits();
        });
        $totalFinancialCustodyRefundValue = $employeeFinancialCustodys->sum(function (FinancialCustody $fin) {
            return $fin->totalRefundedDeposits();
        });
        $totalSmallBorrowValue            = $attendances->sum('borrowValue');
        $totalWorkingHoursInSeconds       = $attendances->sum('totalWorkingHoursInSeconds');
        $totalHoursSalary                 = round(($totalWorkingHoursInSeconds * (($hourlyRate / 60) / 60)), Helpers::getDecimalPointCount());
        $totalWorkingHours                = Helpers::hoursMinutsToString($totalWorkingHoursInSeconds);
        $LongBorrowValue                  = 0;
        if (count($attendances) > 0) {
            $attendance      = $attendances->first();
            $att             = new Attendance($attendance);
            $LongBorrowValue = $att->employeeLongBorrow();
            if ($LongBorrowValue > 0) {
                $attendance['borrowValue'] += $LongBorrowValue;
                $attendance['notes']       = "{$LongBorrowValue} دفعة من السلفة المستديمة";
                $attendances->put($attendances->first()['id'], $attendance);
            }
        }
        $totalLongBorrowValue = $LongBorrowValue;
        $totalBorrowValue     = $totalSmallBorrowValue + $totalLongBorrowValue;
        $totalSalary          = ($totalHoursSalary + $totalBonuses);
        $totalNetSalary       = $totalSalary - ($totalSalaryDeduction + $totalAbsentDeduction + ($totalFinancialCustodyValue - $totalFinancialCustodyRefundValue) + $totalSmallBorrowValue + $totalLongBorrowValue);

        $salaryIsPaid    = FALSE;
        $depositWithdraw = DepositWithdraw::where([
                                                      ['employee_id', '=', $employee->id],
                                                      ['expenses_id', '=', EmployeeActions::TakeSalary]
                                                  ])
                                          ->whereYear('notes', $date->year)
                                          ->whereMonth('notes', $date->month)
                                          ->first();
        if (empty($depositWithdraw)) {
            $salaryIsPaid = TRUE;
        } else {
            $salaryIsPaid = FALSE;
        }
        return [
            'employeeName'                     => $employeeName,
            'monthNum'                         => $monthNum,
            'attendances'                      => $attendances,
            'hourlyRate'                       => $hourlyRate,
            'totalWorkingHours'                => $totalWorkingHours,
            'totalSalaryDeduction'             => $totalSalaryDeduction,
            'totalAbsentDeduction'             => $totalAbsentDeduction,
            'totalBonuses'                     => $totalBonuses,
            'totalSalary'                      => $totalSalary,
            'totalHoursSalary'                 => $totalHoursSalary,
            'totalNetSalary'                   => $totalNetSalary,
            'totalFinancialCustodyValue'       => $totalFinancialCustodyValue,
            'totalFinancialCustodyRefundValue' => $totalFinancialCustodyRefundValue,
            'totalBorrowValue'                 => $totalBorrowValue,
            'totalLongBorrowValue'             => $totalLongBorrowValue,
            'totalSmallBorrowValue'            => $totalSmallBorrowValue,
            'salaryIsPaid'                     => $salaryIsPaid
        ];
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("financialCustody/{employee_id}", as="salary.financialCustody")
     */
    public function financialCustody(Request $request, $employee_id)
    {
        $employees = Employee::all();
        $dt        = DateTime::parse($request->date);
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        if ($employee_id == "all") {
            $employeeFinancialCustodys = []; //Attendance::all();
            $employee_id               = 0;
            $date                      = null;
        } else {
            $employee                  = Employee::findOrFail($employee_id);
            $employeeFinancialCustodys = $employee->employeeFinancialCustodys($dt);
        }
        $date                             = $request->date;
        $employee_id                      = $employee_id;
        $totalFinancialCustodyValue       = 0;
        $totalFinancialCustodyRefundValue = 0;

        foreach ($employeeFinancialCustodys as $financialCustody) {
            $totalFinancialCustodyValue       += $financialCustody->withdrawValue;
            $totalFinancialCustodyRefundValue += $financialCustody->depositValue;
        }
        $employees = $employees_tmp;
        return view("salary.financialCustody", compact(['employees', 'employeeFinancialCustodys', 'totalFinancialCustodyValue', 'totalFinancialCustodyRefundValue', 'employee_id', 'date']));
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("financialCustodyaway/{employee_id}", as="salary.financialCustodyaway")
     * @throws Exception
     */
    public function financialCustodyAway(Request $request, $employee_id)
    {
        $c_user_id = auth()->user()->id;
        DB::beginTransaction();
        try {
            $currentFinancialCustody = $this->getCurrentFinancialCustody($employee_id);
            $employee                = Employee::find($employee_id);
            $allItemsApproved        = !$currentFinancialCustody->hasNotApprovedItems();
            $awayDate                = DateTime::parse($request->date)->lastOfMonth();
            $dueDate                 = DateTime::parse($request->date)->addMonth(1)->firstOfMonth();
            if ($awayDate->isFriday()) {
                $awayDate = $awayDate->subDay(1);
            }
            if ($dueDate->isFriday()) {
                $dueDate = $awayDate->addDay(1);
            }
            if ($allItemsApproved) {
                $depositValue = ($currentFinancialCustody->totalDeposits() - $currentFinancialCustody->totalRefundedDeposits());
                DepositWithdraw::create([
                                            'depositValue'         => $depositValue,
                                            'recordDesc'           => "رد {$currentFinancialCustody->description}",
                                            'employee_id'          => $currentFinancialCustody->employee_id,
                                            'expenses_id'          => EmployeeActions::FinancialCustodyRefund,
                                            'financial_custody_id' => $currentFinancialCustody->id,
                                            'user_id'              => $c_user_id,
                                            'payMethod'            => PaymentMethods::CASH,
                                            'due_date'             => $awayDate
                                        ]);
                $currentFinancialCustody->approved_at = DateTime::now();
                $currentFinancialCustody->approved_by = auth()->user()->id;
                $currentFinancialCustody->save();
                $monthName           = $dueDate->getMonthName();
                $newFinancialCustody = [
                    'user_id'     => $c_user_id,
                    'description' => "عهدة شراء شهر {$monthName} ",
                    'notes'       => '',
                    'approved_by' => null,
                    'approved_at' => null,
                    'due_date'    => $dueDate
                ];
                $newFinancialCustody = $employee->financialCustodies()->create($newFinancialCustody);
                DepositWithdraw::create([
                                            'withdrawValue'        => $depositValue,
                                            'recordDesc'           => "باقي العهدة المرحلة {$currentFinancialCustody->description}",
                                            'employee_id'          => $currentFinancialCustody->employee_id,
                                            'expenses_id'          => EmployeeActions::FinancialCustody,
                                            'financial_custody_id' => $newFinancialCustody->id,
                                            'user_id'              => $c_user_id,
                                            'payMethod'            => PaymentMethods::CASH,
                                            'due_date'             => $dueDate
                                        ]);
                DB::commit();
                return redirect()->back()->with('success', 'تم الترحيل');
            } else {
                return redirect()->back()->with('error', 'لم يتم الترحيل! يجب الموافقة علي باقي بنود العهدة الحالية');
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', "حدث خطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    private function getCurrentFinancialCustody($employee_id)
    {
        $currentEmployee         = Employee::find($employee_id);
        $currentFinancialCustody = $currentEmployee->currentFinancialCustody();

        if ($currentFinancialCustody == null) {
            abort(403, 'عفوا لا يوجد عهدة مسجلة. ');
        }
        return $currentFinancialCustody;
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("longBorrowaway/{employee_id}", as="salary.longBorrowAway")
     */
    public function longBorrowAway(Request $request, $employee_id)
    {
        $date = DateTime::parse($request->date);

        $employeeBorrowBilling        = DB::table('employees')
                                          ->join('employee_borrows', 'employee_borrows.employee_id', '=', 'employees.id')
                                          ->join('employee_borrow_billing', 'employee_borrow_billing.employee_borrow_id', '=', 'employee_borrows.id')
                                          ->distinct()
                                          ->where([
                                                      ['paying_status', '=', EmployeeBorrowBilling::UN_PAID],
                                                      ['employees.id', '=', $employee_id]
                                                  ])
                                          ->whereYear('due_date', $date->year)
                                          ->whereMonth('due_date', $date->month)
                                          ->select('employee_borrow_billing.id')
                                          ->get();
        $data                         = collect(EmployeeBorrowBilling::findOrFail($employeeBorrowBilling->first()->id))->except(['id']);
        $borrowBilling                = EmployeeBorrowBilling::create($data->all());
        $borrowBilling->paying_status = EmployeeBorrowBilling::POSTPONED;
        $borrowBilling->paid_date     = DateTime::now();
        $borrowBilling->paid_amount   = 0;
        $borrowBilling->save();

        foreach ($employeeBorrowBilling as $key => $row) {
            $borrowBilling           = EmployeeBorrowBilling::findOrFail($row->id);
            $borrowBilling->due_date = DateTime::parse($borrowBilling->due_date)->addMonth(1);
            $borrowBilling->save();
        }

        return redirect()->back()->with('success', 'تم الترحيل');
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("printSalaryReport/{employee}", as="salary.printSalaryReport")
     */
    public function printSalaryReport(Request $request, Employee $employee)
    {
        $pdfReport              = new Salary(TRUE);
        $pdfReport->htmlContent = view('reports.employee.salary')->with($this->getData($employee, $request->date))->render();
        return $pdfReport->exportPDF();
    }

    /**
     * @return \Illuminate\Http\Response
     * @Post("paySalary/{employee_id}", as="salary.payEmpolyeeSalary")
     */
    public function payEmpolyeeSalary(Request $request, $employee_id)
    {
        //try {
        $employee             = Employee::findOrFail($employee_id);
        $dt                   = DateTime::parse($request->date);
        $all['due_date']      = DateTime::today();
        $all['withdrawValue'] = $request->totalNetSalary;
        $all['recordDesc']    = "دفع مرتب {$employee->name}";
        $all['employee_id']   = $employee_id;
        $all['payMethod']     = PaymentMethods::CASH;
        $all['expenses_id']   = EmployeeActions::TakeSalary;
        $all['notes']         = $dt;

        $depositWithdraw = DepositWithdraw::where([
                                                      ['employee_id', '=', $employee_id],
                                                      ['expenses_id', '=', EmployeeActions::TakeSalary]
                                                  ])
                                          ->whereYear('notes', $dt->year)
                                          ->whereMonth('notes', $dt->month)
                                          ->first();

        $employeeBorrowBilling = DB::table('employees')
                                   ->join('employee_borrows', 'employee_borrows.employee_id', '=', 'employees.id')
                                   ->join('employee_borrow_billing', 'employee_borrow_billing.employee_borrow_id', '=', 'employee_borrows.id')
                                   ->distinct()
                                   ->where([
                                               ['paying_status', '=', EmployeeBorrowBilling::UN_PAID],
                                               ['employees.id', '=', $employee_id]
                                           ])
                                   ->whereYear('due_date', $dt->year)
                                   ->whereMonth('due_date', $dt->month)
                                   ->select('employee_borrow_billing.id')
                                   ->first();
        if (!empty($employeeBorrowBilling->id)) {
            $borrowBilling                = EmployeeBorrowBilling::findOrFail($employeeBorrowBilling->id);
            $borrowBilling->paid_amount   += $borrowBilling->getRemaining();
            $borrowBilling->paid_date     = $all['due_date'];
            $borrowBilling->paying_status = EmployeeBorrowBilling::PAID;
            $borrowBilling->save();
        }

        if (empty($depositWithdraw)) {
            DepositWithdraw::create($all);
            return redirect()->back()->with('success', 'تم دفع المرتب');
        } else {
            return redirect()->back()->withInput($request->all())->with('error', 'لقد تم دفع المرتب من قبل');
        }
        //} catch (Exception $exc) {
        //    return redirect()->back()->withInput($request->all())->with('error', 'حدث حطأ في حفظ البيانات.');
        //}
    }

}
