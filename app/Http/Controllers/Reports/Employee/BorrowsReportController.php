<?php

namespace App\Http\Controllers\Reports\Employee;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Reports\Employee\TotalSalaries;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="/reports/employees/borrow")
 * @Middleware({"web", "auth"})
 */
class BorrowsReportController extends Controller {

    protected function validator(array $data) {
        $validator = Validator::make($data, [
        ]);

        $validator->setAttributeNames([
        ]);

        return $validator;
    }

    /**
     * Show the Index Page
     * @Get("/", as="reports.employees.borrow.index")
     */
    public function index() {
        $employees = Employee::all();
        $employees_tmp = [];
        $index = 0;
        foreach ($employees as $employee) {
            $employees_tmp[$index]['id'] = $employee->id;
            $employees_tmp[$index]['name'] = $employee->name;
            $employees_tmp[$index]['totalLongBorrows'] = $employee->totalLongBorrows();
            $employees_tmp[$index]['totalPaid'] = $employee->totalPaidBorrow();
            $employees_tmp[$index]['totalRemaining'] = $employee->totalUnpaidBorrow();
            $employees_tmp[$index]['totalSmallBorrows'] = $employee->totalSmallBorrows();
            $index++;
        }
        $employees = $employees_tmp;

        return view('reports.employee.borrow.index', compact("employees"));
    }

    /**
     * Show the Index Page
     * @Any("view-report", as="reports.employees.borrow.viewReport")
     */
    public function viewReport(Request $request) {
        //{"ch_detialed":"0","employee_id":"1","borrowes":["1","2"]}
        $employeeName = "";
        $allEmployeesTotalPrice = 0;
        $allEmployeesTotalPaid = 0;
        $allEmployeesTotalRemaining = 0;
        $employees = [];
        foreach ($request->selectedIds as $id) {
            $employee = Employee::findOrFail($id);
            $employees[$id]['employeeName'] = $employee->name;
            $employees[$id]['employeeNum'] = $employee->id;
            if ($request->ch_longBorrow == TRUE) {

                $employees[$id]['employeeTotalPrice'] = $employee->totalLongBorrows();
                $employees[$id]['employeeTotalPaid'] = $employee->totalPaidBorrow();
                $employees[$id]['employeeTotalRemaining'] = $employee->totalUnpaidBorrow();

                $allEmployeesTotalPrice += $employees[$id]['employeeTotalPrice'];
                $allEmployeesTotalPaid += $employees[$id]['employeeTotalPaid'];
                $allEmployeesTotalRemaining += $employees[$id]['employeeTotalRemaining'];


                $index = 0;
                $employees[$id]['borrowDetails'] = [];
                foreach ($employee->longBorrows as $borrow) {
                    $employees[$id]['borrowDetails'][$index]['desc'] = $borrow->borrow_reason;
                    $employees[$id]['borrowDetails'][$index]['totalPrice'] = $borrow->amount;
                    $employees[$id]['borrowDetails'][$index]['paid'] = $borrow->totalPaid();
                    $employees[$id]['borrowDetails'][$index]['remaining'] = $borrow->totalRemaining();
                    $employees[$id]['borrowDetails'][$index]['date'] = $borrow->created_at;
                    $employees[$id]['borrowDetails'][$index]['notes'] = "";
                    $x = 0;
                    foreach ($borrow->payAmounts as $amount) {
                        $employees[$id]['borrowDetails'][$index]['amounts'][$x] = [
                            'desc' => '', //$amount,
                            'paid_amount' => $amount->paid_amount,
                            'paying_status' => $amount->getStatus(),
                            'due_date' => $amount->due_date,
                            'paid_date' => $amount->paid_date,
                            'notes' => $amount->notes
                        ];
                        $x++;
                    }
                    $index++;
                }
                
            } else {
                $employees[$id]['employeeTotalPrice'] = $employee->totalSmallBorrows();
                $employees[$id]['employeeTotalPaid'] = 'N/A';//$employee->totalPaidBorrow();
                $employees[$id]['employeeTotalRemaining'] = 'N/A';//$employee->totalUnpaidBorrow();

                $allEmployeesTotalPrice += $employees[$id]['employeeTotalPrice'];
                $allEmployeesTotalPaid = 'N/A';//+= $employees[$id]['employeeTotalPaid'];
                $allEmployeesTotalRemaining = 'N/A';//+= $employees[$id]['employeeTotalRemaining'];

                
                $index = 0;
                $employees[$id]['borrowDetails'] = [];
                foreach ($employee->smallBorrows as $borrow) {
                    $employees[$id]['borrowDetails'][$index]['desc'] = $borrow->recordDesc;
                    $employees[$id]['borrowDetails'][$index]['totalPrice'] = $borrow->withdrawValue;
                    $employees[$id]['borrowDetails'][$index]['paid'] = 'N/A';
                    $employees[$id]['borrowDetails'][$index]['remaining'] = 'N/A';
                    $employees[$id]['borrowDetails'][$index]['date'] = $borrow->due_date;
                    $employees[$id]['borrowDetails'][$index]['notes'] = $borrow->notes;
                    $index++;
                }
            }
        }

        session([
            'employeeName' => "",
            'employees' => $employees,
            'allEmployeesTotalPrice' => $allEmployeesTotalPrice,
            'allEmployeesTotalPaid' => $allEmployeesTotalPaid,
            'allEmployeesTotalRemaining' => $allEmployeesTotalRemaining
        ]);
        if ($request->ch_longBorrow == TRUE) {
            return view("reports.employee.borrow..viewLong", compact('employeeName', 'employees', 'allEmployeesTotalPrice', 'allEmployeesTotalPaid', 'allEmployeesTotalRemaining'));
        } else {
            return view("reports.employee.borrow..viewSmall", compact('employeeName', 'employees', 'allEmployeesTotalPrice', 'allEmployeesTotalPaid', 'allEmployeesTotalRemaining'));
        }
    }

    /**
     * Show the Index Page
     * @Get("/print-pdf")
     */
    public function printTotalPDF(Request $request) {
        return $this->printPDF($request->ch_detialed, $request->withLetterHead, session('employees'), session('monthName'), session('totalWorkingHours'), session('totalHourlyRate'), session('totalSalary'), session('totalAbsentDeduction'), session('totalLongBorrowValue'), session('totalSmallBorrowValue'), session('totalBorrowValue'), session('totalSalaryDeduction'), session('totalBonuses'), session('totalGuardianshipValue'), session('totalGuardianshipReturnValue'), session('totalNetSalary'));
    }

    private function printPDF($ch_detialed, $withLetterHead, $employees, $monthName, $totalWorkingHours, $totalHourlyRate, $totalSalary, $totalAbsentDeduction, $totalLongBorrowValue, $totalSmallBorrowValue, $totalBorrowValue, $totalSalaryDeduction, $totalBonuses, $totalGuardianshipValue, $totalGuardianshipReturnValue, $totalNetSalary) {
        $pdfReport = new TotalSalaries($withLetterHead);
        $pdfReport->htmlContent = view("reports.employee.borrow.pdf", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalNetSalary'))->render();
        return $pdfReport->RenderReport();
    }

    function diffInHoursMinutsToString($totalDuration) {
        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration / 60) % 60);
        $seconds = $totalDuration % 60;

        return "$hours:$minutes:$seconds";
    }

}
