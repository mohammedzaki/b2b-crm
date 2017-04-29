<?php

namespace App\Http\Controllers\Reports;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Reports\Employee\TotalSalaries;
use Illuminate\Http\Request;
use Validator;
use function session;
use function view;

class EmployeesReportController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //$this->middleware('ability:admin,employees-permissions');
    }

    protected function validator(array $data) {
        $validator = Validator::make($data, [
                    'name' => 'required|min:6|max:255',
                    'ssn' => 'required|digits:14',
                    'gender' => 'required|in:m,f',
                    'martial_status' => 'in:single,married,widowed,divorced',
                    'birth_date' => 'required|date_format:Y-m-d',
                    'department' => 'string',
                    'hiring_date' => 'required|date_format:Y-m-d',
                    'daily_salary' => 'required|numeric',
                    'working_hours' => 'required|numeric',
                    'job_title' => 'required|max:100',
                    'telephone' => 'digits:8',
                    'mobile' => 'required|digits:11',
                    'can_not_use_program' => 'boolean',
                    'is_active' => 'boolean',
                    'borrow_system' => 'boolean',
                    'username' => 'unique:users,username',
                    'password' => 'min:4'
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم الموظف',
            'ssn' => 'الرقم القومي',
            'gender' => 'الجنس',
            'martial_status' => 'الحالة اﻻجتماعية',
            'birth_date' => 'تاريخ الميﻻد',
            'department' => 'القسم',
            'hiring_date' => 'تاريخ التعيين',
            'daily_salary' => 'الراتب اليومي',
            'working_hours' => 'ساعات العمل',
            'job_title' => 'الوظيفة',
            'telephone' => 'التليفون',
            'mobile' => 'المحمول',
            'can_not_use_program' => 'عدم استخدام البرنامج',
            'is_active' => 'نشط',
            'borrow_system' => 'نظام السلف',
            'username' => 'اسم المستخدم',
            'password' => 'كلمة المرور'
        ]);

        return $validator;
    }

    public function index() {
        $employees = Employee::all(['id', 'name']);
        return view('reports.employee.index', compact("employees"));
    }

    public function viewReport(Request $request) {
        //{"ch_detialed":"0","expense_id":"1","processes":["1","2"]}
        $monthName = "يناير";

        $employees = [];
        $dt = DateTime::parse($request->selectMonth);
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
            $HoursSalary = round(($WorkingHours * (($hourlyRate / 60) / 60)), 2);
            $Salary = ($HoursSalary + $Bonuses);
            $NetSalary = $Salary - ($SalaryDeduction + $AbsentDeduction + ($GuardianshipValue - $GuardianshipReturnValue) + $SmallBorrowValue + $LongBorrowValue);

            $employees[$index] = [
                'name' => $employee->name,
                'workingHours' => $this->diffInHoursMinutsToString($WorkingHours),
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
        
        $totalWorkingHours = $this->diffInHoursMinutsToString($totalWorkingHours);
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

    public function printTotalPDF(Request $request) {
        return $this->printPDF($request->ch_detialed, $request->withLetterHead, session('employees'), session('monthName'), session('totalWorkingHours'), session('totalHourlyRate'), session('totalSalary'), session('totalAbsentDeduction'), session('totalLongBorrowValue'), session('totalSmallBorrowValue'), session('totalBorrowValue'), session('totalSalaryDeduction'), session('totalBonuses'), session('totalGuardianshipValue'), session('totalGuardianshipReturnValue'), session('totalNetSalary'));
    }

    private function printPDF($ch_detialed, $withLetterHead, $employees, $monthName, $totalWorkingHours, $totalHourlyRate, $totalSalary, $totalAbsentDeduction, $totalLongBorrowValue, $totalSmallBorrowValue, $totalBorrowValue, $totalSalaryDeduction, $totalBonuses, $totalGuardianshipValue, $totalGuardianshipReturnValue, $totalNetSalary) {
        $pdfReport = new TotalSalaries($withLetterHead);
        $pdfReport->htmlContent = view("reports.employee.totalSalariesPrint", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalNetSalary'))->render();
        return $pdfReport->RenderReport();
    }

    function diffInHoursMinutsToString($totalDuration) {
        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration / 60) % 60);
        $seconds = $totalDuration % 60;

        return "$hours:$minutes:$seconds";
    }

}
