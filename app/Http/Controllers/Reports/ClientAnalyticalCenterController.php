<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Reports;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Reports\Client\ClientAnalyticalCenterDetailed;
use App\Reports\Client\ClientAnalyticalCenterTotal;
use Illuminate\Http\Request;
use Validator;

/**
 * Description of ClientAnalyticalCenterController
 *
 * @author mohammedzaki
 */
class ClientAnalyticalCenterController extends Controller {

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
        $clients = Client::allHasOpenProcess();
        $clients_tmp = [];
        $index = 0;
        foreach ($clients as $client) {
            $clients_tmp[$index]['id'] = $client->id;
            $clients_tmp[$index]['name'] = $client->name;
            $clients_tmp[$index]['totalDeal'] = $client->getTotalDeal();
            $clients_tmp[$index]['totalPaid'] = $client->getTotalPaid();
            $clients_tmp[$index]['totalRemaining'] = $client->getTotalRemaining();
            $index++;
        }
        $clients = $clients_tmp;

        return view('reports.ClientAnalyticalCenter.index', compact("clients"));
    }

    public function viewReport(Request $request) {
        //{"ch_detialed":"0","client_id":"1","processes":["1","2"]}
        $clientName = "";
        $allClientsTotalPrice = 0;
        $allClientsTotalPaid = 0;
        $allClientsTotalRemaining = 0;

        $clients = [];
        foreach ($request->selectedIds as $id) {
            $client = Client::findOrFail($id);
            $clients[$id]['clientName'] = $client->name;
            $clients[$id]['clientNum'] = $client->id;
            
            $clients[$id]['clientTotalPrice'] = $client->getTotalDeal();
            $clients[$id]['clientTotalPaid'] = $client->getTotalPaid();
            $clients[$id]['clientTotalRemaining'] = $client->getTotalRemaining();
            
            $allClientsTotalPrice += $clients[$id]['clientTotalPrice'];
            $allClientsTotalPaid += $clients[$id]['clientTotalPaid'];
            $allClientsTotalRemaining += $clients[$id]['clientTotalRemaining'];

            if ($request->ch_detialed == TRUE) {
                $index = 0;
                $clients[$id]['processDetails'] = [];
                foreach ($client->processes as $process) {
                    $clients[$id]['processDetails'][$index]['name'] = $process->name;
                    $clients[$id]['processDetails'][$index]['totalPrice'] = $process->total_price_taxes;
                    $clients[$id]['processDetails'][$index]['paid'] = $process->totalDeposits();
                    $clients[$id]['processDetails'][$index]['remaining'] = $process->total_price_taxes - $process->totalDeposits();
                    $clients[$id]['processDetails'][$index]['date'] = $process->created_at;
                    $index++;
                }
            }
        }

        session([
            'clientName' => "",
            'clients' => $clients,
            'allClientsTotalPrice' => $allClientsTotalPrice,
            'allClientsTotalPaid' => $allClientsTotalPaid,
            'allClientsTotalRemaining' => $allClientsTotalRemaining
        ]);
        if ($request->ch_detialed == FALSE) {
            return view("reports.ClientAnalyticalCenter.total", compact('clientName', 'clients', 'allClientsTotalPrice', 'allClientsTotalPaid', 'allClientsTotalRemaining'));
        } else {
            return view("reports.ClientAnalyticalCenter.detialed", compact('clientName', 'clients', 'allClientsTotalPrice', 'allClientsTotalPaid', 'allClientsTotalRemaining'));
        }
    }

    public function printTotalPDF(Request $request) {
        return $this->printClientPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('clients'), session('allClientsTotalPrice'), session('allClientsTotalPaid'), session('allClientsTotalRemaining'));
    }

    public function printDetailedPDF(Request $request) {
        return $this->printClientPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('clients'), session('allClientsTotalPrice'), session('allClientsTotalPaid'), session('allClientsTotalRemaining'));
    }

    private function printClientPDF($ch_detialed, $withLetterHead, $clientName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
        if ($ch_detialed == FALSE) {
            $pdfReport = new ClientAnalyticalCenterTotal($withLetterHead);
        } else {
            $pdfReport = new ClientAnalyticalCenterDetailed($withLetterHead);
        }
        $pdfReport->clientName = $clientName;
        $pdfReport->proceses = $proceses;
        $pdfReport->allProcessesTotalPrice = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->RenderReport();
    }

}
