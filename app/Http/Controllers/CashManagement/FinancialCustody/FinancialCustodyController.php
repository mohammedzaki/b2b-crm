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
use App\Models\FinancialCustody;
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
 * @Middleware({"web", "auth", "ability:admin,financial-custody-items"})
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
     * @param Request $request
     * @return FinancialCustody|null
     */
    private function getCurrentFinancialCustody(Request $request)
    {
        $employee_id        = $request->get('employee_id');
        $financialCustodyId = $request->get('f_id');
        $currentEmployee    = Employee::find(auth()->user()->employee_id);
        if (isset($employee_id) && $employee_id != null) {
            $currentEmployee = Employee::find($employee_id);
        }
        if (isset($financialCustodyId)) {
            $currentFinancialCustody = FinancialCustody::find($financialCustodyId);
        } else {
            $currentFinancialCustody = $currentEmployee->currentFinancialCustody();
        }
        if ($currentFinancialCustody == null) { // || count($currentFinancialCustody->deposits) <= 0) {
            abort(403, 'عفوا لا يوجد عهدة مسجلة. ');
        }
        $date = DateTime::parse($request->due_date);
        if ($date->month !== DateTime::parse($currentFinancialCustody->due_date)->month) {
            throw new ValidationException(' يجب ان التاريخ موافق لشهر العهدة  او اضغط ترحيل  باقي العهدة و فتح عهدة للشهر الجديد ');
        }
        return $currentFinancialCustody;
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("show-my-custodians", as="financialCustody.showMyCustodians")
     * @Middleware({"web", "auth", "ability:admin,financial-custody-items"})
     */
    public function showCurrentEmployeeCustodians(Request $request)
    {
        return $this->getEmployeeCustodians($request, auth()->user()->employee_id, "financial-custody.show-my-custodians", false);
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("employee-custody/{employee_id}", as="financialCustody.employeeCustody")
     */
    public function employeeCustody(Request $request, $employee_id)
    {
        return $this->getEmployeeCustodians($request, $employee_id);
    }

    private function getEmployeeCustodians(Request $request, $employee_id, $viewName = "financial-custody.employee-custody", $showAll = true)
    {
        $employees = [];
        if ($showAll) {
            $employees = Employee::all();
        } else {
            $employees = Employee::where("id", "=", $employee_id)->get();
        }
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
            if (isset($request->date) & !empty($request->date)) {
                $employeeFinancialCustodys = $employee->employeeFinancialCustodys($request->date);
            } else {
                $employeeFinancialCustodys = $employee->employeeFinancialCustodys();
            }
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
        return view($viewName, compact(['employees', 'employeeFinancialCustodys', 'totalFinancialCustodyValue', 'totalFinancialCustodyRefundValue', 'employee_id', 'date']));
    }
}