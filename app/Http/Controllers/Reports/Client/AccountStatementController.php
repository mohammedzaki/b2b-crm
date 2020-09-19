<?php

namespace App\Http\Controllers\Reports\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Extensions\DateTime;
use App\Reports\V2\Client\ClientTotal;
use App\Reports\V2\Client\ClientDetailed;

/**
 * @Controller(prefix="/reports/client/account-statement")
 * @Middleware({"web", "auth"})
 */
class AccountStatementController extends Controller
{

    /**
     * Show the Index Page
     * @Get("/", as="reports.client.accountStatement.index")
     */
    public function index()
    {
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

        return view('reports.client.account-statement.index', compact("clients", "clientProcesses"));
    }

    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.client.accountStatement.viewReport")
     */
    public function viewReport(Request $request)
    {
        if ($request->ch_detialed == FALSE) {
            $pdfReport = new ClientTotal($request->withLetterHead);
        } else {
            $pdfReport = new ClientDetailed($request->withLetterHead);
        }
        $pdfReport->clientId = $request->client_id;
        $pdfReport->processIds = $request->processes;
        session([
                    'clientId'   => $request->client_id,
                    'processIds' => $request->processes
                ]);
        return $pdfReport->preview();
    }

    /**
     * Show the Index Page
     * @Get("/print-total-pdf", as="reports.client.accountStatement.printTotalPDF")
     */
    public function printTotalPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('clientId'), session('processIds'));
    }

    /**
     * Show the Index Page
     * @Get("/print-detailed-pdf", as="reports.client.accountStatement.printDetailedPDF")
     */
    public function printDetailedPDF(Request $request)
    {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('clientId'), session('processIds'));
    }

    private function exportPDF($ch_detailed, $withLetterHead, $clientId, $processIds)
    {
        if ($ch_detailed == FALSE) {
            $pdfReport = new ClientTotal($withLetterHead);
        } else {
            $pdfReport = new ClientDetailed($withLetterHead);
        }
        $pdfReport->clientId = $clientId;
        $pdfReport->processIds = $processIds;
        return $pdfReport->exportPDF();
    }

}
