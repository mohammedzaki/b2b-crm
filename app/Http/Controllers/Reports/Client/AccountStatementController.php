<?php

namespace App\Http\Controllers\Reports\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Extensions\DateTime;
use App\Reports\Client\ClientTotal;
use App\Reports\Client\ClientDetailed;

/**
 * @Controller(prefix="/reports/client/account-statement")
 * @Middleware({"web", "auth"})
 */
class AccountStatementController extends Controller {
    
    /**
     * Show the Index Page
     * @Get("/", as="reports.client.accountStatement.index")
     */
    public function index() {
        $clients = Client::all();
        $clients_tmp = [];
        $clientProcesses = [];
        $index = 0;
        foreach ($clients as $client) {
            $clients_tmp[$index]['id'] = $client->id;
            $clients_tmp[$index]['name'] = $client->name;
            $clients_tmp[$index]['hasOpenProcess'] = $client->hasOpenProcess();
            $clients_tmp[$index]['hasClosedProcess'] = $client->hasClosedProcess();

            foreach ($client->processes as $process) {
                $clientProcesses[$client->id][$process->id]['name'] = $process->name;
                $clientProcesses[$client->id][$process->id]['totalPrice'] = $process->total_price;
                $clientProcesses[$client->id][$process->id]['status'] = $process->status;
            }
            $index++;
        }
        $clients = json_encode($clients_tmp);

        $clientProcesses = json_encode($clientProcesses);

        return view('reports.Client.AccountStatement.index', compact("clients", "clientProcesses"));
    }

    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.client.accountStatement.viewReport")
     */
    public function viewReport(Request $request) {
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
            $proceses[$id]['processTotalPaid'] = $clientProcess->totalDeposits() + $clientProcess->discount_value;
            $proceses[$id]['processTotalRemaining'] = $clientProcess->total_price_taxes - $clientProcess->totalDeposits();
            $proceses[$id]['processDate'] = DateTime::today()->format('Y-m-d'); //Print Date
            $proceses[$id]['processNum'] = $id;
            $allProcessesTotalPrice += $proceses[$id]['processTotalPrice'];
            $allProcessTotalPaid += $proceses[$id]['processTotalPaid'];
            $allProcessTotalRemaining += $proceses[$id]['processTotalRemaining'];

            if ($request->ch_detialed == TRUE) {
                $index = 0;
                $totalDepositValue = 0;
                foreach ($clientProcess->items as $item) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($item->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $item->quantity * $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = $item->unit_price;
                    $proceses[$id]['processDetails'][$index]['quantity'] = $item->quantity;
                    $proceses[$id]['processDetails'][$index]['desc'] = $item->description;
                    $index++;
                }
                if ($clientProcess->has_discount == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($clientProcess->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = $clientProcess->discount_value;
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم بسبب : " . $clientProcess->discount_reason;
                    $index++;
                }
                if ($clientProcess->has_source_discount == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($clientProcess->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->source_discount_value;
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "خصم من المنبع";
                    $index++;
                }
                if ($clientProcess->require_invoice == TRUE) {
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($clientProcess->created_at)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = "";
                    $proceses[$id]['processDetails'][$index]['paid'] = "";
                    $proceses[$id]['processDetails'][$index]['totalPrice'] = $clientProcess->taxesValue();
                    $proceses[$id]['processDetails'][$index]['unitPrice'] = "";
                    $proceses[$id]['processDetails'][$index]['quantity'] = "";
                    $proceses[$id]['processDetails'][$index]['desc'] = "قيمة الضريبة المضافة";
                    $index++;
                }
                foreach ($clientProcess->deposits as $deposit) {
                    $totalDepositValue += $deposit->depositValue;
                    $proceses[$id]['processDetails'][$index]['date'] = DateTime::parse($deposit->due_date)->format('Y-m-d');
                    $proceses[$id]['processDetails'][$index]['remaining'] = $clientProcess->total_price_taxes - $totalDepositValue;
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
        if ($request->ch_detialed == FALSE) {
            return view("reports.Client.AccountStatement.total", compact('clientName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        } else {
            return view("reports.Client.AccountStatement.detialed", compact('clientName', 'proceses', 'allProcessesTotalPrice', 'allProcessTotalPaid', 'allProcessTotalRemaining'));
        }
    }

    /**
     * Show the Index Page
     * @Get("/print-total-pdf", as="reports.client.accountStatement.printTotalPDF")
     */
    public function printTotalPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    /**
     * Show the Index Page
     * @Get("/print-detailed-pdf", as="reports.client.accountStatement.printDetailedPDF")
     */
    public function printDetailedPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('clientName'), session('proceses'), session('allProcessesTotalPrice'), session('allProcessTotalPaid'), session('allProcessTotalRemaining'));
    }

    private function exportPDF($ch_detialed, $withLetterHead, $clientName, $proceses, $allProcessesTotalPrice, $allProcessTotalPaid, $allProcessTotalRemaining) {
        if ($ch_detialed == FALSE) {
            $pdfReport = new ClientTotal($withLetterHead);
        } else {
            $pdfReport = new ClientDetailed($withLetterHead);
        }
        $pdfReport->clientName = $clientName;
        $pdfReport->proceses = $proceses;
        $pdfReport->allProcessesTotalPrice = $allProcessesTotalPrice;
        $pdfReport->allProcessTotalPaid = $allProcessTotalPaid;
        $pdfReport->allProcessTotalRemaining = $allProcessTotalRemaining;
        return $pdfReport->exportPDF();
    }

}
