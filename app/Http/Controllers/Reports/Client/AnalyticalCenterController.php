<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Reports\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Reports\Client\ClientAnalyticalCenterDetailed;
use App\Reports\Client\ClientAnalyticalCenterTotal;
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

        return view('reports.Client.AnalyticalCenter.index', compact("clients"));
    }

    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.client.analyticalCenter.viewReport")
     */
    public function viewReport(Request $request)
    {
        $clientName               = "";
        $allClientsTotalPrice     = 0;
        $allClientsTotalPaid      = 0;
        $allClientsTotalRemaining = 0;

        $clients = [];
        foreach ($request->selectedIds as $id) {
            $client                               = Client::findOrFail($id);
            $clients[$id]['clientName']           = $client->name;
            $clients[$id]['clientNum']            = $client->id;
            $clients[$id]['clientTotalPrice']     = 0;
            $clients[$id]['clientTotalPaid']      = 0;
            $clients[$id]['clientTotalRemaining'] = 0;
            $index                                = 0;
            $clients[$id]['processDetails']       = [];
            foreach ($client->processes as $process) {
                if ($request->ch_detialed == TRUE) {
                    $clients[$id]['processDetails'][$index]['name']       = $process->name;
                    $clients[$id]['processDetails'][$index]['totalPrice'] = $process->total_price_taxes;
                    $clients[$id]['processDetails'][$index]['paid']       = $process->totalDeposits();
                    $clients[$id]['processDetails'][$index]['remaining']  = $process->totalRemaining();
                    $clients[$id]['processDetails'][$index]['date']       = $process->created_at;
                }
                $clients[$id]['clientTotalRemaining'] += $process->totalRemaining();
                $clients[$id]['clientTotalPaid']      += $process->totalDeposits();
                $clients[$id]['clientTotalPrice']     += $process->total_price_taxes;
                $index++;
            }

            $allClientsTotalPrice     += $clients[$id]['clientTotalPrice'];
            $allClientsTotalPaid      += $clients[$id]['clientTotalPaid'];
            $allClientsTotalRemaining += $clients[$id]['clientTotalRemaining'];

        }

        session([
                    'clientName'               => "",
                    'clients'                  => $clients,
                    'allClientsTotalPrice'     => $allClientsTotalPrice,
                    'allClientsTotalPaid'      => $allClientsTotalPaid,
                    'allClientsTotalRemaining' => $allClientsTotalRemaining
                ]);
        if ($request->ch_detialed == FALSE) {
            return view("reports.Client.AnalyticalCenter.total", compact('clientName', 'clients', 'allClientsTotalPrice', 'allClientsTotalPaid', 'allClientsTotalRemaining'));
        } else {
            return view("reports.Client.AnalyticalCenter.detialed", compact('clientName', 'clients', 'allClientsTotalPrice', 'allClientsTotalPaid', 'allClientsTotalRemaining'));
        }
    }

    /**
     * Show the Index Page
     * @Get("/print-total-pdf", as="reports.client.analyticalCenter.printTotalPDF")
     */
    public function printTotalPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('clients'), session('allClientsTotalPrice'), session('allClientsTotalPaid'), session('allClientsTotalRemaining'));
    }

    private function exportPDF($ch_detialed, $withLetterHead, $clientName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining)
    {
        if ($ch_detialed == FALSE) {
            $pdfReport = new ClientAnalyticalCenterTotal($withLetterHead);
        } else {
            $pdfReport = new ClientAnalyticalCenterDetailed($withLetterHead);
        }
        $pdfReport->clientName               = $clientName;
        $pdfReport->proceses                 = $proceses;
        $pdfReport->allProcessesTotalPrice   = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid      = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->exportPDF();
    }

    /**
     * Show the Index Page
     * @Get("/print-detailed-pdf", as="reports.client.analyticalCenter.printDetailedPDF")
     */
    public function printDetailedPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('clients'), session('allClientsTotalPrice'), session('allClientsTotalPaid'), session('allClientsTotalRemaining'));
    }

}
