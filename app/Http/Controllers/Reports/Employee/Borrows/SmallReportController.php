<?php

namespace App\Http\Controllers\Reports\Employee\Borrows;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Reports\Employee\TotalSalaries;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="/reports/employees/borrow/small")
 * @Middleware({"web", "auth"})
 */
class SmallReportController extends Controller {

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
        ]);

        $validator->setAttributeNames([
        ]);

        return $validator;
    }

    /**
     * Show the Index Page
     * @Get("/", as="reports.employees.borrow.small.index")
     */
    public function index()
    {
        $employees = Employee::all()->mapWithKeys(function ($employee) {
            return [
                $employee['id'] => [
                    'id'                => $employee['id'],
                    'name'              => $employee['name'],
                    'totalSmallBorrows' => $employee->totalSmallBorrows()
                ]
            ];
        });
        return view('reports.employee.borrow.small.index', compact('employees'));
    }

    /**
     * Show the Index Page
     * @Any("view-report", as="reports.employees.borrow.small.viewReport")
     */
    public function viewReport(Request $request)
    {
        //{"ch_detialed":"0","employee_id":"1","borrowes":["1","2"]}
        $employeeName               = "";
        $allEmployeesTotalPrice     = 0;
        $allEmployeesTotalPaid      = 0;
        $allEmployeesTotalRemaining = 0;
        $employees                  = [];
        foreach ($request->selectedIds as $id) {
            $employee                       = Employee::findOrFail($id);
            $employees[$id]['employeeName'] = $employee->name;
            $employees[$id]['employeeNum']  = $employee->id;

            $employees[$id]['employeeTotalPrice']     = $employee->totalSmallBorrows();
            $employees[$id]['employeeTotalPaid']      = 'N/A'; //$employee->totalPaidBorrow();
            $employees[$id]['employeeTotalRemaining'] = 'N/A'; //$employee->totalUnpaidBorrow();

            $allEmployeesTotalPrice     += $employees[$id]['employeeTotalPrice'];
            $allEmployeesTotalPaid      = 'N/A'; //+= $employees[$id]['employeeTotalPaid'];
            $allEmployeesTotalRemaining = 'N/A'; //+= $employees[$id]['employeeTotalRemaining'];


            $index                           = 0;
            $employees[$id]['borrowDetails'] = [];
            foreach ($employee->smallBorrows as $borrow) {
                $employees[$id]['borrowDetails'][$index]['desc']       = $borrow->recordDesc;
                $employees[$id]['borrowDetails'][$index]['totalPrice'] = $borrow->withdrawValue;
                $employees[$id]['borrowDetails'][$index]['paid']       = 'N/A';
                $employees[$id]['borrowDetails'][$index]['remaining']  = 'N/A';
                $employees[$id]['borrowDetails'][$index]['date']       = $borrow->due_date;
                $employees[$id]['borrowDetails'][$index]['notes']      = $borrow->notes;
                $index++;
            }
        }

        session([
            'employeeName'               => "",
            'employees'                  => $employees,
            'allEmployeesTotalPrice'     => $allEmployeesTotalPrice,
            'allEmployeesTotalPaid'      => $allEmployeesTotalPaid,
            'allEmployeesTotalRemaining' => $allEmployeesTotalRemaining
        ]);
        return view("reports.employee.borrow.small.view", compact('employeeName', 'employees', 'allEmployeesTotalPrice', 'allEmployeesTotalPaid', 'allEmployeesTotalRemaining'));
    }

    /**
     * Show the Index Page
     * @Get("/print-pdf")
     */
    public function printTotalPDF(Request $request)
    {
        return $this->printPDF($request->ch_detialed, $request->withLetterHead, session('employees'), session('monthName'), session('totalWorkingHours'), session('totalHourlyRate'), session('totalSalary'), session('totalAbsentDeduction'), session('totalLongBorrowValue'), session('totalSmallBorrowValue'), session('totalBorrowValue'), session('totalSalaryDeduction'), session('totalBonuses'), session('totalGuardianshipValue'), session('totalGuardianshipReturnValue'), session('totalNetSalary'));
    }

    private function printPDF($ch_detialed, $withLetterHead, $employees, $monthName, $totalWorkingHours, $totalHourlyRate, $totalSalary, $totalAbsentDeduction, $totalLongBorrowValue, $totalSmallBorrowValue, $totalBorrowValue, $totalSalaryDeduction, $totalBonuses, $totalGuardianshipValue, $totalGuardianshipReturnValue, $totalNetSalary)
    {
        $pdfReport              = new TotalSalaries($withLetterHead);
        $pdfReport->htmlContent = view("reports.employee.borrow.small.pdf", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalNetSalary'))->render();
        return $pdfReport->exportPDF();
    }

}
