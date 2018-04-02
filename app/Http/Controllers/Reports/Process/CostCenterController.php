<?php

namespace App\Http\Controllers\Reports\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Extensions\DateTime;
use App\Reports\Process\CostCenter;

/**
 * @Controller(prefix="/reports/process/cost-center")
 * @Middleware({"web", "auth"})
 */
class CostCenterController extends Controller {

    public $report;

    public function __construct() {
        $this->report = new CostCenter();
    }

    /**
     * Show the Index Page
     * @Get("/", as="reports.process.cost-center.index")
     */
    public function index() {
        $clients         = Client::all();
        $clients_tmp     = [];
        $clientProcesses = [];
        $index           = 0;
        foreach ($clients as $client) {
            $clients_tmp[$index]['id']               = $client->id;
            $clients_tmp[$index]['name']             = $client->name;
            $clients_tmp[$index]['hasOpenProcess']   = $client->hasOpenProcess();
            $clients_tmp[$index]['hasClosedProcess'] = $client->hasClosedProcess();

            foreach ($client->processes as $process) {
                $clientProcesses[$client->id][$process->id]['name']       = $process->name;
                $clientProcesses[$client->id][$process->id]['totalPrice'] = $process->total_price;
                $clientProcesses[$client->id][$process->id]['status']     = $process->status;
            }
            $index++;
        }
        $clients         = json_encode($clients_tmp);
        $clientProcesses = json_encode($clientProcesses);
        return view('reports.process.cost-center.index', compact("clients", "clientProcesses"));
    }

    /**
     * Show the Index Page
     * @Any("view-report", as="reports.process.cost-center.view-report")
     */
    public function viewReport(Request $request) {
        $this->report->clientId = $request->client_id;
        $this->report->processIds = $request->processes;
        return $this->report->preview();
    }

    /**
     * Show the Index Page
     * @Get("print-pdf", as="reports.process.cost-center.print-pdf")
     */
    public function printPDF(Request $request) {
        $this->report->clientId = $request->client_id;
        $this->report->processIds = $request->processes;
        return $this->report->exportPDF();
    }

}
