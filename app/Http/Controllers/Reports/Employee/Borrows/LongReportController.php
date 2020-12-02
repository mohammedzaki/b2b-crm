<?php

namespace App\Http\Controllers\Reports\Employee\Borrows;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBorrow;
use App\Models\Employee;
use App\Reports\Employee\TotalSalaries;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="/reports/employees/borrow/long")
 * @Middleware({"web", "auth"})
 */
class LongReportController extends Controller
{

    /**
     * Show the Index Page
     * @Get("/", as="reports.employees.borrow.long.index")
     */
    public function index()
    {
        $employees = Employee::all()->mapWithKeys(function ($employee) {
            return [
                $employee['id'] => [
                    'id'               => $employee['id'],
                    'name'             => $employee['name'],
                    'hasUnpaidBorrows' => $employee->hasUnpaidBorrow(),
                    'borrows'          => $employee->longBorrows->mapWithKeys(function ($borrow) {
                        return [
                            $borrow['id'] => [
                                'id'             => $borrow->id,
                                'borrow_reason'  => $borrow->borrow_reason,
                                'total'          => $borrow->amount,
                                'totalPaid'      => $borrow->totalPaid(),
                                'totalRemaining' => $borrow->totalRemaining(),
                                'active'         => count($borrow->unPaidAmounts) > 0
                            ]
                        ];
                    }),
                ]
            ];
        });
        return view('reports.employee.borrow.long.index', compact('employees'));
    }

    /**
     * Show the Index Page
     * @Any("view-report", as="reports.employees.borrow.long.viewReport")
     */
    public function viewReport(Request $request)
    {

        $emp                      = Employee::findOrFail($request->employee_id);
        $employee['employeeName'] = $emp->name;
        $employee['employeeNum']  = $emp->id;

        $employee['borrowDetails']          = EmployeeBorrow::whereIn('id', $request->selectedIds)->get()->mapWithKeys(function ($borrow) {
            return [
                $borrow->id => [
                    'desc'       => $borrow->borrow_reason,
                    'totalPrice' => $borrow->amount,
                    'paid'       => $borrow->totalPaid(),
                    'remaining'  => $borrow->totalRemaining(),
                    'date'       => $borrow->created_at,
                    'notes'      => '',
                    'amounts'    => $borrow->payAmounts->mapWithKeys(function ($amount) {
                        return [
                            $amount->id => [
                                'desc'          => '',
                                'paid_amount'   => $amount->paid_amount,
                                'paying_status' => $amount->getStatus(),
                                'due_date'      => $amount->due_date,
                                'paid_date'     => $amount->paid_date,
                                'notes'         => $amount->notes
                            ]
                        ];
                    })
                ]
            ];
        });
        $employee['employeeTotalPrice']     = $employee['borrowDetails']->sum('totalPrice');
        $employee['employeeTotalPaid']      = $employee['borrowDetails']->sum('paid');
        $employee['employeeTotalRemaining'] = $employee['borrowDetails']->sum('remaining');

        return view("reports.employee.borrow.long.view", compact('employee'));
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
        $pdfReport->htmlContent = view("reports.employee.borrow.long.pdf", compact('employees', 'monthName', 'totalWorkingHours', 'totalHourlyRate', 'totalSalary', 'totalAbsentDeduction', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'totalBorrowValue', 'totalSalaryDeduction', 'totalBonuses', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalNetSalary'))->render();
        return $pdfReport->exportPDF();
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
        ]);

        $validator->setAttributeNames([
                                      ]);

        return $validator;
    }

}
