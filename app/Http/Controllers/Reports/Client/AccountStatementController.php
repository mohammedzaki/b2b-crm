<?php

namespace App\Http\Controllers\Reports\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
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
     * Show the ViewReport Page
     * @Get("/view-report", as="reports.client.accountStatement.viewReport")
     */
    public function viewReport(Request $request)
    {
        return $this->getReport($request)->preview();
    }

    /**
     * Show the PrintPDF Page
     * @Get("/print-pdf", as="reports.client.accountStatement.printPDF")
     */
    public function printPDF(Request $request)
    {
        return $this->getReport($request)->exportPDF();
    }

    private function getReport(Request $request)
    {
        if ($request->ch_detialed == FALSE) {
            return new ClientTotal($request->withLetterHead, $request->client_id, $request->processes);
        } else {
            return new ClientDetailed($request->withLetterHead, $request->client_id, $request->processes);
        }
    }

}
