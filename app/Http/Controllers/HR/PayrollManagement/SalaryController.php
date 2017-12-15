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
use App\Http\Controllers\Controller;
use App\Models\AbsentType;
use App\Models\Attendance;
use App\Models\ClientProcess;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\EmployeeBorrowBilling;
use App\Reports\Employee\Salary;
use Illuminate\Http\Request;
use DB;
use Exception;
use Auth;
use Validator;

/**
 * Description of SalaryController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 * 
 * @Controller(prefix="salary")
 * @Middleware({"web", "auth"})
 */
class SalaryController {

    /**
     * Display the specified resource.
     *
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     * @Get("{employee_id}", as="salary.show")
     */
    public function show(Request $request, $employee_id)
    {
        $employees = Employee::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        });
        $dt         = DateTime::parse($request->date);
        $hourlyRate = 0;
        $hasData    = FALSE;
        if ($employee_id == "all") {
            $attendances = []; //Attendance::all();
            $employee_id = 0;
            $date        = null;
        } else {
            $employee    = Employee::findOrFail($employee_id);
            $hourlyRate  = $employee->salaryPerHour();
            $attendances = Attendance::where([
                        ['employee_id', '=', $employee_id]
                    ])->whereMonth('date', '=', $dt->month)->orderBy('date', 'asc')->get();
            $hasData     = TRUE;
        }
        $date                         = $request->date;
        $totalWorkingHours            = 0;
        $totalSalaryDeduction         = 0;
        $totalAbsentDeduction         = 0;
        $totalBonuses                 = 0;
        $totalHoursSalary             = 0;
        $totalSalary                  = 0;
        $totalNetSalary               = 0;
        $totalGuardianshipValue       = 0;
        $totalGuardianshipReturnValue = 0;
        $totalBorrowValue             = 0;
        $totalSmallBorrowValue        = 0;
        $totalLongBorrowValue         = 0;
        foreach ($attendances as $attendance) {
            $attendance->workingHours = $attendance->workingHoursToString();
            $attendance->employeeName = $attendance->employee->name;
            $attendance->date         = DateTime::parse($attendance->date)->format('l, d-m-Y');

            $attendance->GuardianshipValue       = $attendance->employeeGuardianship();
            $attendance->GuardianshipReturnValue = $attendance->employeeGuardianshipReturn();
            $attendance->borrowValue             = $attendance->employeeSmallBorrow();

            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
            $totalWorkingHours            += $attendance->workingHoursToSeconds();
            $totalSalaryDeduction         += $attendance->salary_deduction;
            $totalAbsentDeduction         += $attendance->absent_deduction;
            $totalBonuses                 += $attendance->mokaf;
            $totalGuardianshipValue       += $attendance->GuardianshipValue;
            $totalGuardianshipReturnValue += $attendance->GuardianshipReturnValue;
            $totalBorrowValue             += $attendance->borrowValue;
        }
        try {
            $attendances[0]->borrowValue += $attendances[0]->employeeLongBorrow();
            $totalLongBorrowValue        = $attendances[0]->employeeLongBorrow();
        } catch (Exception $exc) {
            
        }
        $totalSmallBorrowValue = $totalBorrowValue;
        $totalBorrowValue      += $totalLongBorrowValue;
        $totalHoursSalary      = $totalWorkingHours * (($hourlyRate / 60) / 60);
        $totalHoursSalary      = round($totalHoursSalary, 3);
        $totalWorkingHours     = Helpers::hoursMinutsToString($totalWorkingHours);
        $totalSalary           = ($totalHoursSalary + $totalBonuses);
        $totalNetSalary        = $totalSalary - ($totalSalaryDeduction + $totalAbsentDeduction + ($totalGuardianshipValue - $totalGuardianshipReturnValue) + $totalSmallBorrowValue + $totalLongBorrowValue);
        $salaryIsPaid    = FALSE;
        $depositWithdraw = DepositWithdraw::where([
                    ['employee_id', '=', $employee_id],
                    ['expenses_id', '=', EmployeeActions::TakeSalary]
                ])->whereMonth('notes', '=', $dt->month)->first();
        if (empty($depositWithdraw)) {
            $salaryIsPaid = TRUE;
        } else {
            $salaryIsPaid = FALSE;
        }
        return view('salary.index', compact(['employees', 'attendances', "hourlyRate", "totalWorkingHours", "totalSalaryDeduction", "totalAbsentDeduction", "totalBonuses", "totalSalary", 'totalHoursSalary', 'totalNetSalary', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalBorrowValue', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'employee_id', 'date', 'hasData', 'salaryIsPaid']));
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("guardianship/{employee_id}", as="salary.guardianship")
     */
    public function guardianship(Request $request, $employee_id)
    {
        $employees = Employee::all();
        $dt        = DateTime::parse($request->date);
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        if ($employee_id == "all") {
            $employeeGuardianships = []; //Attendance::all();
            $employee_id           = 0;
            $date                  = null;
        } else {
            $employee              = Employee::findOrFail($employee_id);
            $employeeGuardianships = $employee->employeeGuardianships($dt);
        }
        $date                         = $request->date;
        $employee_id                  = $employee_id;
        $totalGuardianshipValue       = 0;
        $totalGuardianshipReturnValue = 0;

        foreach ($employeeGuardianships as $guardianship) {
            $totalGuardianshipValue       += $guardianship->withdrawValue;
            $totalGuardianshipReturnValue += $guardianship->depositValue;
        }
        $employees = $employees_tmp;
        return view("salary.guardianship", compact(['employees', 'employeeGuardianships', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'employee_id', 'date']));
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("guardianshipaway/{employee_id}", as="salary.guardianshipaway")
     */
    public function guardianshipaway(Request $request, $employee_id)
    {
        $employee               = Employee::findOrFail($employee_id);
        $newDate                = DateTime::parse($request->date);
        $newDate->addMonth(1);
        $depositWithdraw        = DepositWithdraw::findOrFail($employee->lastGuardianshipId());
        $depositWithdraw->notes = $newDate->startOfMonth();
        $depositWithdraw->save();
        return redirect()->back()->with('success', 'تم الترحيل');
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("guardianshipback/{employee_id}", as="salary.guardianshipback")
     */
    public function guardianshipback(Request $request, $employee_id)
    {
        $employee               = Employee::findOrFail($employee_id);
        $depositWithdraw        = DepositWithdraw::findOrFail($employee->lastGuardianshipId());
        $depositWithdraw->notes = $depositWithdraw->due_date;
        $depositWithdraw->save();
        return redirect()->back()->with('success', 'تم الغاء ترحيل العهدة');
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
                ->whereMonth('due_date', '>=', $date->month)
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
        $hourlyRate                   = 0;
        $totalWorkingHours            = 0;
        $totalSalaryDeduction         = 0;
        $totalAbsentDeduction         = 0;
        $totalBonuses                 = 0;
        $totalHoursSalary             = 0;
        $totalSalary                  = 0;
        $totalNetSalary               = 0;
        $totalGuardianshipValue       = 0;
        $totalGuardianshipReturnValue = 0;
        $totalBorrowValue             = 0;
        $totalSmallBorrowValue        = 0;
        $totalLongBorrowValue         = 0;

        $date        = DateTime::parse($request->date);
        $hourlyRate  = $employee->salaryPerHour();
        $attendances = Attendance::where([
                    ['employee_id', '=', $employee->id]
                ])->whereMonth('date', '=', $date->month)->get();

        $monthNum      = $date->month;
        $employees_tmp = [];
        foreach ($attendances as $attendance) {
            $attendance->workingHours            = $attendance->workingHoursToString();
            $attendance->employeeName            = $attendance->employee->name;
            $attendance->GuardianshipValue       = $attendance->employeeGuardianship();
            $attendance->GuardianshipReturnValue = $attendance->employeeGuardianshipReturn();
            $attendance->borrowValue             = $attendance->employeeSmallBorrow();

            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
            $totalWorkingHours            += $attendance->workingHoursToSeconds();
            $totalSalaryDeduction         += $attendance->salary_deduction;
            $totalAbsentDeduction         += $attendance->absent_deduction;
            $totalBonuses                 += $attendance->mokaf;
            $totalGuardianshipValue       += $attendance->GuardianshipValue;
            $totalGuardianshipReturnValue += $attendance->GuardianshipReturnValue;
            $totalBorrowValue             += $attendance->borrowValue;
        }
        try {
            $attendances[0]->borrowValue = $attendances[0]->employeeLongBorrow();
            $totalLongBorrowValue        = $attendances[0]->employeeLongBorrow();
        } catch (Exception $exc) {
            
        }
        $totalSmallBorrowValue = $totalBorrowValue;
        $totalBorrowValue      += $totalLongBorrowValue;
        $totalHoursSalary      = $totalWorkingHours * (($hourlyRate / 60) / 60);
        $totalHoursSalary      = round($totalHoursSalary, 3);
        $totalSalary           = ($totalHoursSalary + $totalBonuses);
        $totalNetSalary        = $totalSalary - ($totalSalaryDeduction + $totalAbsentDeduction + ($totalGuardianshipValue - $totalGuardianshipReturnValue) + $totalSmallBorrowValue + $totalLongBorrowValue);

        $employeeName = $employee->name;

        $pdfReport = new Salary(TRUE);

        $pdfReport->htmlContent = view('reports.employee.salary', compact(['employeeName', 'attendances', "hourlyRate", "totalWorkingHours", "totalSalaryDeduction", "totalAbsentDeduction", "totalBonuses", "totalSalary", 'totalHoursSalary', 'totalNetSalary', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalBorrowValue', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'employee_id', 'date', 'monthNum']))->render();

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
                ])->whereMonth('notes', '=', $dt->month)->first();

        $employeeBorrowBilling = DB::table('employees')
                ->join('employee_borrows', 'employee_borrows.employee_id', '=', 'employees.id')
                ->join('employee_borrow_billing', 'employee_borrow_billing.employee_borrow_id', '=', 'employee_borrows.id')
                ->distinct()
                ->where([
                    ['paying_status', '=', EmployeeBorrowBilling::UN_PAID],
                    ['employees.id', '=', $employee_id]
                ])
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

    /**
     * @return \Illuminate\Http\Response
     * @Get("getEmployeesCheckinDate", as="salary.getEmployeesCheckinDate")
     */
    public function getEmployeesCheckinDate(Request $request)
    {
        $shift      = ($request->is_second_shift == "true" ? 2 : 1);
        $attendance = Attendance::where([
                    ["date", "=", $request->date],
                    ["employee_id", "=", $request->employee_id],
                    ["shift", "=", $shift]
                ])->first();
        if (empty($attendance))
            return "";
        else
            return $attendance->check_in;
    }

}
