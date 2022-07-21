<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Reports\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Reports\V2\Client\ClientAnalyticalCenterDetailed;
use App\Reports\V2\Client\ClientAnalyticalCenterTotal;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="/reports/client/analytical-center")
 * @Middleware({"web", "auth"})
 */
class AnalyticalCenterController extends Controller
{

    /**
     * Show the Index Page
     * @Get("/", as="reports.client.analyticalCenter.index")
     */
    public function index()
    {
        $clients     = Client::allHasOpenProcess();
        $clients_tmp = [];
        $index       = 0;
        foreach ($clients as $client) {
            $clients_tmp[$index]['id']             = $client->id;
            $clients_tmp[$index]['name']           = $client->name;
            $clients_tmp[$index]['totalDeal']      = $client->getTotalDeal();
            $clients_tmp[$index]['totalPaid']      = $client->getTotalPaid();
            $clients_tmp[$index]['totalRemaining'] = $client->getTotalRemaining();
            $index++;
        }
        $clients = $clients_tmp;

        return view('reports.client.analytical-center.index', compact("clients"));
    }

    /**
     * Show the ViewReport Page
     * @Get("/view-report", as="reports.client.analyticalCenter.viewReport")
     */
    public function viewReport(Request $request)
    {
        return $this->getReport($request)->preview();
    }

    /**
     * Show the PrintPDF Page
     * @Get("/print-pdf", as="reports.client.analyticalCenter.printPDF")
     */
    public function printPDF(Request $request)
    {
        return $this->getReport($request)->exportPDF();
    }

    private function getReport(Request $request)
    {
        if ($request->ch_detialed == FALSE) {
            return new ClientAnalyticalCenterTotal($request->withLetterHead, $request->selectedIds);
        } else {
            return new ClientAnalyticalCenterDetailed($request->withLetterHead, $request->selectedIds);
        }
    }

}
