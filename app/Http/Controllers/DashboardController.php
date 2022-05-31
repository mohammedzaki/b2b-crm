<?php

namespace App\Http\Controllers;

use App\Constants\ChequeStatuses;
use App\Http\Requests;
use App\Models\BankCashItem;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\Expenses;
use App\Models\Facility;
use App\Models\Supplier;
use App\Models\SupplierProcess;

/**
 * @Controller(prefix="/")
 * @Middleware({"web"})
 */
class DashboardController extends
    Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @Get("/home", as="home")
     * @Get("/", as="home")
     * @Middleware({"auth"})
     */
    public function index()
    {
        $numbers['clients_number']         = Client::count();
        $numbers['suppliers_number']       = Supplier::count();
        $numbers['process_number']         = ClientProcess::count();
        $numbers['Supplierprocess_number'] = SupplierProcess::count();
        // TODO: must re-program
        $numbers['current_amount'] = ((DepositWithdraw::sum('depositValue') + Facility::sum('opening_amount')) - DepositWithdraw::sum('withdrawValue'));
        $clients                   = Client::select('id', 'name')->get();
        $employees                 = Employee::select('id', 'name')->get();
        $suppliers                 = Supplier::select('id', 'name')->get();
        $expenses                  = Expenses::all();
        $clients_tmp               = [];
        $employees_tmp             = [];
        $suppliers_tmp             = [];
        $expenses_tmp              = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($expenses as $expense) {
            $expenses_tmp[$expense->id] = $expense->name;
        }
        $clients   = $clients_tmp;
        $employees = $employees_tmp;
        $suppliers = $suppliers_tmp;
        $expenses  = $expenses_tmp;
        return view('dashboard', compact(['numbers', 'clients', 'employees', 'suppliers', 'expenses']));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @Get("/getCalendarItems", as="getCalendarItems")
     * @Middleware({"auth"})
     */
    public function getCalendarItems()
    {
        $bankCashItems = BankCashItem::whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED])->get();
        return $bankCashItems->mapWithKeys(function (BankCashItem $item, $index) {
            return [
                $index => [
                    "title"           => $item->getTitle() . ' ... ' . $item->getDescription(),
                    "start"           => $item->cashing_date,
                    "description"     => $item->getDescription(),
                    "backgroundColor" => isset($item->withdrawValue) ? 'red' : 'green',
                    "borderColor"     => isset($item->withdrawValue) ? 'red' : 'green',
                    "end"             => $item->cashing_date
                ]
            ];
        });
    }
}
