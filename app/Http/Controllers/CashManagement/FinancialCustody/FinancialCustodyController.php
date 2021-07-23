<?php
/**
 * Created by PhpStorm.
 * User: mohamedzaki
 * Date: 12/7/20
 * Time: 9:15 PM
 */

namespace App\Http\Controllers\CashManagement\FinancialCustody;

use App\Constants\EmployeeActions;
use App\Constants\PaymentMethods;
use App\Exceptions\ValidationException;
use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\EmployeeBorrowBilling;
use App\Models\Expenses;
use App\Models\OpeningAmount;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Helpers\Helpers;

/**
 * Description of DailyCashController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="/financial-custody")
 * @Resource("financialCustody")
 * @Middleware({"web", "auth", "ability:admin,financial-custody"})
 */
class FinancialCustodyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all()->mapWithKeys(function ($employee) {
            return [$employee->id => $employee->name];
        });
        return view('financial-custody.index')->with([
                                                         'employees'                 => $employees,
                                                         'employee_id'               => null,
                                                         'date'                      => null,
                                                         'employeeFinancialCustodys' => []
                                                     ]);
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("employee-custody/{employee_id}", as="financialCustody.employeeCustody")
     */
    public function employeeCustody(Request $request, $employee_id)
    {
        $employees = Employee::all();
        // $dt        = DateTime::parse();
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        if ($employee_id == "all") {
            $employeeFinancialCustodys = []; //Attendance::all();
            $employee_id               = 0;
            $date                      = null;
        } else {
            $employee                  = Employee::findOrFail($employee_id);
            $employeeFinancialCustodys = $employee->employeeFinancialCustodys($request->date);
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
        return view("financial-custody.employee-custody", compact(['employees', 'employeeFinancialCustodys', 'totalFinancialCustodyValue', 'totalFinancialCustodyRefundValue', 'employee_id', 'date']));
    }
}