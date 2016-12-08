<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\User;
use App\Role;
use App\Permission;
use App\Client;
use App\ClientProcess;
use App\DepositWithdraw;
use App\Reports\Client\ClientTotal;
use App\Reports\Client\ClientDetailed;

class ReportsController extends Controller {

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
        return view('reports.index');
    }

    public function client() {
        $clients = Client::all();
        $clients_tmp = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        $clients = $clients_tmp;
        return view('reports.client.index', compact("clients"));
    }

    public function viewClientReport(Request $request) {
        //{"ch_detialed":"0","client_id":"1","processes":["1","2"]}
        $client = Client::findOrFail($request->client_id);
        $clientName = $client->name;
        $allProcessesTotalPrice = 0;
        $allProcessTotalPaid = 0;
        $allProcessTotalRemaining = 0;

        $proceses = [];
        foreach ($request->processes as $id) {
            $clientProcess = ClientProcess::findOrFail($id);
            $proceses[$id]['processName'] = $clientProcess->name;
            $proceses[$id]['processTotalPrice'] = $clientProcess->total_price;
            $proceses[$id]['processTotalPaid'] = $clientProcess->deposits()->sum('depositValue');
            $proceses[$id]['processTotalRemaining'] = $clientProcess->total_price - $clientProcess->deposits()->sum('depositValue');
            $proceses[$id]['processDate'] = $clientProcess->created_at;
            $allProcessesTotalPrice += $proceses[$id]['processTotalPrice'];
            $allProcessTotalPaid += $proceses[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $proceses[$id]['processTotalRemaining'];

            if ($request->ch_detialed == "1") {
                $index = 0;
                $totalDepositValue = 0;
                foreach ($clientProcess->items as $item) {
                    $proceses[$id]['processDetails'][$index]['date'] = $item->updated_at;
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['quantity'] = $item->quantity;
                    $proceses[$id]['processDetails'][$index]['desc'] = $item->description;
                    $index++;
                }
                foreach ($clientProcess->deposits as $deposit) {
                    $totalDepositValue += $deposit->depositValue;
                    $proceses[$id]['processDetails'][$index]['date'] = $deposit->updated_at;
                    $proceses[$id]['processDetails'][$index]['remaining'] = $clientProcess->total_price - $totalDepositValue;
                    $proceses[$id]['processDetails'][$index]['paid'] = $deposit->depositValue;
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = $deposit->recordDesc;
                    $index++;
                }
            }
        }
        session([
                'clientName' => $clientName,
                'proceses' => $proceses, 
                'allProcessesTotalPrice' => $allProcessesTotalPrice, 
                'allProcessTotalPaid' => $allProcessTotalPaid, 
                'allProcessTotalRemaining' => $allProcessTotalRemaining
                ]);
        if ($request->ch_detialed == "0") {
            return view("reports.client.total", compact('clientName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        } else {
            return view("reports.client.detialed", compact('clientName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        }
    }

    public function printTotalPDF() {
        // Instanciation of inherited class
        $pdfReport = new ClientTotal();
        $pdfReport->clientName = session('clientName');
        $pdfReport->proceses = session('proceses');
        $pdfReport->allProcessesTotalPrice = session('allProcessesTotalPrice');
        $pdfReport->allProcessTotalPaid = session('allProcessTotalPaid');
        $pdfReport->allProcessTotalRemaining = session('allProcessTotalRemaining');
        return $pdfReport->RenderReport();
    }
    
    public function printDetailedPDF() {
        // Instanciation of inherited class
        $pdfReport = new ClientDetailed();
        $pdfReport->clientName = session('clientName');
        $pdfReport->proceses = session('proceses');
        $pdfReport->allProcessesTotalPrice = session('allProcessesTotalPrice');
        $pdfReport->allProcessTotalPaid = session('allProcessTotalPaid');
        $pdfReport->allProcessTotalRemaining = session('allProcessTotalRemaining');
        return $pdfReport->RenderReport();
    }

}
