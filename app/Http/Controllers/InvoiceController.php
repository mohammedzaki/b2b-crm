<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Reports\Invoice\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $processes = [];
        return view('invoice.index', compact('processes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $clients = Client::allHasInvoiceProcess();
        $clients_tmp = [];
        $clientProcesses = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;

            foreach ($client->unInvoiceProcesses as $process) {
                $clientProcesses[$client->id][$process->id]['name'] = $process->name;
                $clientProcesses[$client->id][$process->id]['totalPrice'] = $process->total_price;
                $clientProcesses[$client->id][$process->id]['totalPriceTaxes'] = $process->total_price_taxes;
                $clientProcesses[$client->id][$process->id]['discount'] = $process->discount_value ? $process->discount_value : 0;
                $clientProcesses[$client->id][$process->id]['sourceDiscount'] = $process->source_discount_value ? $process->source_discount_value : 0;
                $clientProcesses[$client->id][$process->id]['taxes'] = $process->taxes_value ? $process->taxes_value : 0;
                $clientProcesses[$client->id][$process->id]['status'] = $process->status;
                $clientProcesses[$client->id][$process->id]['items'] = $process->items;
            }
        }
        $clients = $clients_tmp;

        $clientProcesses = json_encode($clientProcesses);

        return view('invoice.create', compact("clients", "clientProcesses"));
    }

    public function printPreview(Request $request) {
        $pdfReport = new Invoice(TRUE);
        return $pdfReport->RenderReport();
    }

    public function testPreview(Request $request) {
        $employeeName = 'Mai Gado';
        return view('reports.invoice.invoice', compact(['employeeName']))->render();
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
