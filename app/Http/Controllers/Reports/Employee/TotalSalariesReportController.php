<?php

namespace App\Http\Controllers\Reports\Employee;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Reports\Employee\TotalSalaries;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use Debugbar;

/**
 * @Controller(prefix="/reports/employees/total-salaries")
 * @Middleware({"web", "auth"})
 */
class TotalSalariesReportController extends Controller {

    /**
     * Show the Index Page
     * @Get("/", as="reports.employees.totalSalaries.index")
     */
    public function index()
    {
        $employees = Employee::all(['id', 'name']);
        return view('reports.employee.index', compact("employees"));
    }

    public function viewReportOld(Request $request)
    {
        $employees = [];
        $date      = DateTime::parse($request->selectMonth);
        $monthName = $date->getMonthName();
        $index     = 0;

        $totalWorkingHours            = 0;
        $totalHourlyRate              = 0;
        $totalSalaryDeduction         = 0;
        $totalAbsentDeduction         = 0;
        $totalBonuses                 = 0;
        $totalSalary                  = 0;
        $totalNetSalary               = 0;
        $totalFinancialCustodyValue       = 0;
        $totalFinancialCustodyRefundValue = 0;
        $totalBorrowValue             = 0;
        $totalSmallBorrowValue        = 0;
        $totalLongBorrowValue         = 0;

        foreach ($request->selectedIds as $employee_id) {

            $WorkingHours            = 0;
            $SalaryDeduction         = 0;
            $AbsentDeduction         = 0;
            $Bonuses                 = 0;
            $HoursSalary             = 0;
            $Salary                  = 0;
            $NetSalary               = 0;
            $FinancialCustodyValue       = 0;
            $FinancialCustodyRefundValue = 0;
            $BorrowValue             = 0;
            $SmallBorrowValue        = 0;
            $LongBorrowValue         = 0;
            $hourlyRate              = 0;

            $employee    = Employee::findOrFail($employee_id);
            $hourlyRate  = $employee->daily_salary / $employee->working_hours;
            $attendances = Attendance::where([
                        ['employee_id', '=', $employee_id]
                    ])->whereMonth('date', '=', $date->month)->get();

            foreach ($attendances as $attendance) {
                $WorkingHours            += $attendance->workingHoursToSeconds();
                $SalaryDeduction         += $attendance->salary_deduction;
                $AbsentDeduction         += $attendance->absent_deduction;
                $Bonuses                 += $attendance->mokaf;
                $FinancialCustodyValue       += $attendance->FinancialCustodyValue;
                $FinancialCustodyRefundValue += $attendance->FinancialCustodyRefundValue;
                $BorrowValue             += $attendance->borrowValue;
            }
            try {
                $attendances[0]->borrowValue = $attendances[0]->employeeLongBorrow();
                $LongBorrowValue             = $attendances[0]->employeeLongBorrow();
            } catch (\Exception $exc) {
                
            }
            $HoursSalary = round(($WorkingHours * (($hourlyRate / 60) / 60)), Helpers::getDecimalPointCount());
            $Salary      = ($HoursSalary + $Bonuses);
            $NetSalary   = $Salary - ($SalaryDeduction + $AbsentDeduction + ($FinancialCustodyValue - $FinancialCustodyRefundValue) + $SmallBorrowValue + $LongBorrowValue);

            $employees[$index] = [
                'name'                    => $employee->name,
                'workingHours'            => Helpers::hoursMinutsToString($WorkingHours),
                'hourRate'                => $hourlyRate,
                'salary'                  => $HoursSalary,
                'absentDeduction'         => $AbsentDeduction,
                'longBorrow'              => $LongBorrowValue,
                'smallBorrow'             => $SmallBorrowValue,
                'totalBorrow'             => ($LongBorrowValue + $SmallBorrowValue),
                'salaryDeduction'         => $SalaryDeduction,
                'bonuses'                 => $Bonuses,
                'financialCustodyValue'       => $FinancialCustodyValue,
                'financialCustodyRefundValue' => $FinancialCustodyRefundValue,
                'netSalary'               => $NetSalary,
            ];

            $totalWorkingHours            += $WorkingHours;
            $totalHourlyRate              += $hourlyRate;
            $totalSalary                  += $HoursSalary;
            $totalAbsentDeduction         += $AbsentDeduction;
            $totalLongBorrowValue         += $LongBorrowValue;
            $totalSmallBorrowValue        += $SmallBorrowValue;
            $totalBorrowValue             += ($LongBorrowValue + $SmallBorrowValue);
            $totalSalaryDeduction         += $SalaryDeduction;
            $totalBonuses                 += $Bonuses;
            $totalFinancialCustodyValue       += $FinancialCustodyValue;
            $totalFinancialCustodyRefundValue += $FinancialCustodyRefundValue;
            $totalNetSalary               += $NetSalary;

            $index++;
        }

        $totalWorkingHours = Helpers::hoursMinutsToString($totalWorkingHours);
        session([
            'monthName'                    => $monthName,
            'employees'                    => $employees,
            'totalWorkingHours'            => $totalWorkingHours,
            'totalHourlyRate'              => $totalHourlyRate,
            'totalSalary'                  => $totalSalary,
            'totalAbsentDeduction'         => $totalAbsentDeduction,
            'totalLongBorrowValue'         => $totalLongBorrowValue,
            'totalSmallBorrowValue'        => $totalSmallBorrowValue,
            'totalBorrowValue'             => $totalBorrowValue,
            'totalSalaryDeduction'         => $totalSalaryDeduction,
            'totalBonuses'                 => $totalBonuses,
            'totalFinancialCustodyValue'       => $totalFinancialCustodyValue,
            'totalFinancialCustodyRefundValue' => $totalFinancialCustodyRefundValue,
            'totalNetSalary'               => $totalNetSalary
        ]);
        return view("reports.employee.totalSalaries", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalFinancialCustodyValue', 'totalFinancialCustodyRefundValue', 'totalNetSalary'));
    }

    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.employees.totalSalaries.viewReport")
     */
    public function viewReport(Request $request)
    {
        $employees = [];
        $date      = DateTime::parse($request->selectMonth);
        $monthName = $date->getMonthName();
        $index     = 0;

        foreach ($request->selectedIds as $employee_id) {
            $employee   = Employee::findOrFail($employee_id);
            $hourlyRate = $employee->salaryPerHour();

            $attendances = Attendance::where([
                        ['employee_id', '=', $employee->id]
                    ])
                    ->whereYear('date', '=', $date->year)
                    ->whereMonth('date', '=', $date->month)
                    ->orderBy('date', 'asc')->get()
                    ->mapWithKeys(function ($attendance) {
                return [
                    $attendance->id => [
                        'id'                      => $attendance->id,
                        'WorkingHoursInSeconds'   => $attendance->workingHoursToSeconds(),
                        'salary_deduction'        => $attendance->salary_deduction,
                        'absent_deduction'        => $attendance->absent_deduction,
                        'bonuses'                 => $attendance->mokaf,
                        'FinancialCustodyValue'       => $attendance->employeeFinancialCustody(),
                        'FinancialCustodyRefundValue' => $attendance->employeeFinancialCustodyRefund(),
                        'borrowValue'             => $attendance->employeeSmallBorrow(),
                    //'workingHours'               => $attendance->workingHoursToString(),
                    ]
                ];
            });

            $attendances instanceof \Illuminate\Support\Collection;
            $SalaryDeduction         = $attendances->sum('salary_deduction');
            $AbsentDeduction         = $attendances->sum('absent_deduction');
            $Bonuses                 = $attendances->sum('bonuses');
            $FinancialCustodyValue       = $attendances->sum('FinancialCustodyValue');
            $FinancialCustodyRefundValue = $attendances->sum('FinancialCustodyRefundValue');
            $SmallBorrowValue        = $attendances->sum('borrowValue');
            $WorkingHoursInSeconds   = $attendances->sum('WorkingHoursInSeconds');
            $HoursSalary             = round(($WorkingHoursInSeconds * (($hourlyRate / 60) / 60)), Helpers::getDecimalPointCount());
            $LongBorrowValue         = 0;
            if (count($attendances) > 0) {
                $attendance      = Attendance::find($attendances->first()['id']);
                $LongBorrowValue = $attendance->employeeLongBorrow();
            }
            $Salary            = ($HoursSalary + $Bonuses);
            $NetSalary         = $Salary - ($SalaryDeduction + $AbsentDeduction + ($FinancialCustodyValue - $FinancialCustodyRefundValue) + $SmallBorrowValue + $LongBorrowValue);
            $employees[$index] = [
                'name'                    => $employee->name,
                'workingHours'            => Helpers::hoursMinutsToString($WorkingHoursInSeconds),
                'WorkingHoursInSeconds'   => $WorkingHoursInSeconds,
                'hourRate'                => $hourlyRate,
                'salary'                  => $HoursSalary,
                'absentDeduction'         => $AbsentDeduction,
                'longBorrow'              => $LongBorrowValue,
                'smallBorrow'             => $SmallBorrowValue,
                'totalBorrow'             => ($LongBorrowValue + $SmallBorrowValue),
                'salaryDeduction'         => $SalaryDeduction,
                'bonuses'                 => $Bonuses,
                'financialCustodyValue'       => $FinancialCustodyValue,
                'financialCustodyRefundValue' => $FinancialCustodyRefundValue,
                'netSalary'               => $NetSalary,
            ];
            
            $index++;
        }
        $employees                    = collect($employees);
        $totalWorkingHoursInSeconds   = $employees->sum('WorkingHoursInSeconds');
        $totalHourlyRate              = $employees->sum('hourRate');
        $totalSalary                  = $employees->sum('salary');
        $totalAbsentDeduction         = $employees->sum('absentDeduction');
        $totalLongBorrowValue         = $employees->sum('longBorrow');
        $totalSmallBorrowValue        = $employees->sum('smallBorrow');
        $totalBorrowValue             = ($totalLongBorrowValue + $totalSmallBorrowValue);
        $totalSalaryDeduction         = $employees->sum('salaryDeduction');
        $totalBonuses                 = $employees->sum('bonuses');
        $totalFinancialCustodyValue       = $employees->sum('financialCustodyValue');
        $totalFinancialCustodyRefundValue = $employees->sum('financialCustodyRefundValue');
        $totalNetSalary               = $employees->sum('netSalary');

        $totalWorkingHours = Helpers::hoursMinutsToString($totalWorkingHoursInSeconds);
        session([
            'monthName'                    => $monthName,
            'employees'                    => $employees,
            'totalWorkingHours'            => $totalWorkingHours,
            'totalHourlyRate'              => $totalHourlyRate,
            'totalSalary'                  => $totalSalary,
            'totalAbsentDeduction'         => $totalAbsentDeduction,
            'totalLongBorrowValue'         => $totalLongBorrowValue,
            'totalSmallBorrowValue'        => $totalSmallBorrowValue,
            'totalBorrowValue'             => $totalBorrowValue,
            'totalSalaryDeduction'         => $totalSalaryDeduction,
            'totalBonuses'                 => $totalBonuses,
            'totalFinancialCustodyValue'       => $totalFinancialCustodyValue,
            'totalFinancialCustodyRefundValue' => $totalFinancialCustodyRefundValue,
            'totalNetSalary'               => $totalNetSalary
        ]);
        return view("reports.employee.totalSalaries", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalFinancialCustodyValue', 'totalFinancialCustodyRefundValue', 'totalNetSalary'));
    }

    /**
     * Show the Index Page
     * @Get("/print-pdf", as="reports.employees.totalSalaries.printPDF")
     */
    public function printPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('employees'), session('monthName'), session('totalWorkingHours'), session('totalHourlyRate'), session('totalSalary'), session('totalAbsentDeduction'), session('totalLongBorrowValue'), session('totalSmallBorrowValue'), session('totalBorrowValue'), session('totalSalaryDeduction'), session('totalBonuses'), session('totalFinancialCustodyValue'), session('totalFinancialCustodyRefundValue'), session('totalNetSalary'));
    }

    private function exportPDF($ch_detialed, $withLetterHead, $employees, $monthName, $totalWorkingHours, $totalHourlyRate, $totalSalary, $totalAbsentDeduction, $totalLongBorrowValue, $totalSmallBorrowValue, $totalBorrowValue, $totalSalaryDeduction, $totalBonuses, $totalFinancialCustodyValue, $totalFinancialCustodyRefundValue, $totalNetSalary)
    {
        $pdfReport              = new TotalSalaries($withLetterHead);
        $pdfReport->htmlContent = view("reports.employee.totalSalariesPrint", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalFinancialCustodyValue', 'totalFinancialCustodyRefundValue', 'totalNetSalary'))->render();
        return $pdfReport->exportPDF();
    }

}
