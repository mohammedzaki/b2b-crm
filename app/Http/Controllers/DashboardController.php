<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Client;
use App\Supplier;
use App\ClientProcess;
use App\Employee;
use App\Expenses;
use App\SupplierProcess;
use App\DepositWithdraw;
use App\Facility;
use Validator;

class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $numbers['clients_number'] = Client::count();
        $numbers['suppliers_number'] = Supplier::count();
        $numbers['process_number'] = ClientProcess::count();
        $numbers['Supplierprocess_number'] = SupplierProcess::count();
        // TODO: must re-program
        $numbers['current_amount'] = ((DepositWithdraw::sum('depositValue') + Facility::sum('opening_amount')) - DepositWithdraw::sum('withdrawValue'));

        $clients = Client::select('id', 'name')->get();
        $employees = Employee::select('id', 'name')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $expenses = Expenses::all();
        $clients_tmp = [];
        $employees_tmp = [];
        $suppliers_tmp = [];
        $expenses_tmp = [];
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
        $clients = $clients_tmp;
        $employees = $employees_tmp;
        $suppliers = $suppliers_tmp;
        $expenses = $expenses_tmp;
        return view('dashboard', compact(['numbers', 'clients', 'employees', 'suppliers', 'expenses']));
    }
}
