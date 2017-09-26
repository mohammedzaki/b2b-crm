<?php

namespace App\Http\Controllers\Reports\Employee;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Reports\Employee\TotalSalaries;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

/**
 * @Controller(prefix="/reports/employees/total-salaries")
 * @Middleware({"web", "auth"})
 */
class TotalSalariesReportController extends Controller {

    /**
     * Show the Index Page
     * @Get("/", as="reports.employees.totalSalaries.index")
     */
    public function index() {
        $employees = Employee::all(['id', 'name']);
        return view('reports.employee.index', compact("employees"));
    }
    
    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.employees.totalSalaries.viewReport")
     */
    public function viewReport(Request $request) {
        $employees = [];
        $dt = DateTime::parse($request->selectMonth);
        $monthName = $dt->getMonthName();
        $index = 0;

        $totalWorkingHours = 0;
        $totalHourlyRate = 0;
        $totalSalaryDeduction = 0;
        $totalAbsentDeduction = 0;
        $totalBonuses = 0;
        $totalHoursSalary = 0;
        $totalSalary = 0;
        $totalNetSalary = 0;
        $totalGuardianshipValue = 0;
        $totalGuardianshipReturnValue = 0;
        $totalBorrowValue = 0;
        $totalSmallBorrowValue = 0;
        $totalLongBorrowValue = 0;

        foreach ($request->selectedIds as $employee_id) {

            $WorkingHours = 0;
            $SalaryDeduction = 0;
            $AbsentDeduction = 0;
            $Bonuses = 0;
            $HoursSalary = 0;
            $Salary = 0;
            $NetSalary = 0;
            $GuardianshipValue = 0;
            $GuardianshipReturnValue = 0;
            $BorrowValue = 0;
            $SmallBorrowValue = 0;
            $LongBorrowValue = 0;
            $hourlyRate = 0;

            $employee = Employee::findOrFail($employee_id);
            $hourlyRate = $employee->daily_salary / $employee->working_hours;
            $attendances = Attendance::where([
                        ['employee_id', '=', $employee_id]
                    ])->whereMonth('date', '=', $dt->month)->get();

            foreach ($attendances as $attendance) {
                $WorkingHours += $attendance->workingHoursToSeconds();
                $SalaryDeduction += $attendance->salary_deduction;
                $AbsentDeduction += $attendance->absent_deduction;
                $Bonuses += $attendance->mokaf;
                $GuardianshipValue += $attendance->GuardianshipValue;
                $GuardianshipReturnValue += $attendance->GuardianshipReturnValue;
                $BorrowValue += $attendance->borrowValue;
            }
            try {
                $attendances[0]->borrowValue = $attendances[0]->employeeLongBorrow();
                $LongBorrowValue = $attendances[0]->employeeLongBorrow();
            } catch (\Exception $exc) {
                
            }
            $HoursSalary = round(($WorkingHours * (($hourlyRate / 60) / 60)), 3);
            $Salary = ($HoursSalary + $Bonuses);
            $NetSalary = $Salary - ($SalaryDeduction + $AbsentDeduction + ($GuardianshipValue - $GuardianshipReturnValue) + $SmallBorrowValue + $LongBorrowValue);

            $employees[$index] = [
                'name' => $employee->name,
                'workingHours' => Helpers::hoursMinutsToString($WorkingHours),
                'hourRate' => $hourlyRate,
                'salary' => $HoursSalary,
                'absentDeduction' => $AbsentDeduction,
                'longBorrow' => $LongBorrowValue,
                'smallBorrow' => $SmallBorrowValue,
                'totalBorrow' => ($LongBorrowValue + $SmallBorrowValue),
                'salaryDeduction' => $SalaryDeduction,
                'bonuses' => $Bonuses,
                'guardianshipValue' => $GuardianshipValue,
                'guardianshipReturnValue' => $GuardianshipReturnValue,
                'netSalary' => $NetSalary,
            ];

            $totalWorkingHours += $WorkingHours;
            $totalHourlyRate += $hourlyRate;
            $totalSalary += $HoursSalary;
            $totalAbsentDeduction += $AbsentDeduction;
            $totalLongBorrowValue += $LongBorrowValue;
            $totalSmallBorrowValue += $SmallBorrowValue;
            $totalBorrowValue += ($LongBorrowValue + $SmallBorrowValue);
            $totalSalaryDeduction += $SalaryDeduction;
            $totalBonuses += $Bonuses;
            $totalGuardianshipValue += $GuardianshipValue;
            $totalGuardianshipReturnValue += $GuardianshipReturnValue;
            $totalNetSalary += $NetSalary;

            $index++;
        }

        $totalWorkingHours = Helpers::hoursMinutsToString($totalWorkingHours);
        session([
            'monthName' => $monthName,
            'employees' => $employees,
            'totalWorkingHours' => $totalWorkingHours,
            'totalHourlyRate' => $totalHourlyRate,
            'totalSalary' => $totalSalary,
            'totalAbsentDeduction' => $totalAbsentDeduction,
            'totalLongBorrowValue' => $totalLongBorrowValue,
            'totalSmallBorrowValue' => $totalSmallBorrowValue,
            'totalBorrowValue' => $totalBorrowValue,
            'totalSalaryDeduction' => $totalSalaryDeduction,
            'totalBonuses' => $totalBonuses,
            'totalGuardianshipValue' => $totalGuardianshipValue,
            'totalGuardianshipReturnValue' => $totalGuardianshipReturnValue,
            'totalNetSalary' => $totalNetSalary
        ]);
        return view("reports.employee.totalSalaries", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalNetSalary'));
    }

    /**
     * Show the Index Page
     * @Get("/print-pdf", as="reports.employees.totalSalaries.printPDF")
     */
    public function printPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('employees'), session('monthName'), session('totalWorkingHours'), session('totalHourlyRate'), session('totalSalary'), session('totalAbsentDeduction'), session('totalLongBorrowValue'), session('totalSmallBorrowValue'), session('totalBorrowValue'), session('totalSalaryDeduction'), session('totalBonuses'), session('totalGuardianshipValue'), session('totalGuardianshipReturnValue'), session('totalNetSalary'));
    }

    private function exportPDF($ch_detialed, $withLetterHead, $employees, $monthName, $totalWorkingHours, $totalHourlyRate, $totalSalary, $totalAbsentDeduction, $totalLongBorrowValue, $totalSmallBorrowValue, $totalBorrowValue, $totalSalaryDeduction, $totalBonuses, $totalGuardianshipValue, $totalGuardianshipReturnValue, $totalNetSalary) {
        $pdfReport = new TotalSalaries($withLetterHead);
        $pdfReport->htmlContent = view("reports.employee.totalSalariesPrint", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalNetSalary'))->render();
        return $pdfReport->exportPDF();
    }

}
