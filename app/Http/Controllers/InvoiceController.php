<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientProcess;
use App\Reports\Invoice\Invoice as InvoiceReport;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use DB;

/**
 * @Controller(prefix="invoice")
 * @Resource("invoice")
 * @Middleware("web")
 */
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

    private function setData(Invoice $invoice = NULL) {
        $clients = [];
        if ($invoice) {
            $clients = Client::allHasInvoiceProcess();
        } else {
            $clients = Client::allHasUnBilledInvoiceProcess();
        }
        $clients_tmp = [];
        $clientProcesses = [];
        $invoiceProcesses = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
            if ($invoice) {
                $invoiceProcesses = $client->invoiceProcesses;
            } else {
                $invoiceProcesses = $client->unInvoiceProcesses;
            }
            foreach ($invoiceProcesses as $process) {
                $clientProcesses[$client->id][$process->id] = [
                    'name' => $process->name,
                    'totalPrice' => $process->total_price,
                    'totalPriceTaxes' => $process->total_price_taxes,
                    'discount' => ($process->discount_value ? $process->discount_value : 0),
                    'sourceDiscount' => ($process->source_discount_value ? $process->source_discount_value : 0),
                    'taxes' => ($process->taxes_value ? $process->taxes_value : 0),
                    'status' => $process->status,
                    'items' => $process->items,
                    'totalPaid' => $process->totalDeposits(),
                    'totalRemaining' => $process->totalRemaining()
                ];
            }
        }
        $clients = $clients_tmp;

        $clientProcesses = json_encode($clientProcesses);
        return compact("invoice", "clients", "clientProcesses");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('invoice.create', $this->setData());
    }

    /**
     * Show the Index Page
     * @Post("preview", as="invoice.printPreview")
     */
    public function printPreview(Request $request) {
        $pdfReport = new InvoiceReport(TRUE);
        $index = count($request->invoiceItems) - 1;
        $invoiceItems = $request->invoiceItems;
        $sourceDiscountValue = 0;
        $taxesValue = 0;
        $has_source_discount = FALSE;
        $require_invoice = FALSE;
        foreach ($request->processes as $processId) {
            $clientProcess = ClientProcess::findOrFail($processId);
            if ($clientProcess->has_discount == TRUE) {
                $invoiceItems[++$index] = [
                    'size' => "",
                    'total_price' => $clientProcess->discount_value,
                    'class' => 'redColor',
                    'unit_price' => "",
                    'quantity' => "",
                    'description' => "خصم بسبب : " . $clientProcess->discount_reason,
                ];
            }
            if ($clientProcess->has_source_discount == TRUE) {
                $sourceDiscountValue += $clientProcess->source_discount_value;
                $has_source_discount = TRUE;
            }
            if ($clientProcess->require_invoice == TRUE) {
                $taxesValue += $clientProcess->taxes_value;
                $require_invoice = TRUE;
            }
        }
        if ($has_source_discount) {
            $invoiceItems[++$index] = [
                'size' => "",
                'total_price' => $sourceDiscountValue,
                'class' => 'redColor',
                'unit_price' => "",
                'quantity' => "",
                'description' => "خصم من المنبع",
            ];
        }
        if ($require_invoice) {
            $invoiceItems[++$index] = [
                'size' => "",
                'total_price' => $taxesValue,
                //'class' => 'redColor',
                'unit_price' => "",
                'quantity' => "",
                'description' => "قيمة الضريبة المضافة",
            ];
        }
        $client = Client::findOrFail($request->client_id);
        $pdfReport->clinetName = $client->name;
        $pdfReport->invoiceItems = $invoiceItems;
        $pdfReport->discountPrice = $request->discount_price;
        $pdfReport->discountReason = 'N\A';
        $pdfReport->invoiceDate = $request->invoice_date;
        $pdfReport->invoiceNo = $request->invoice_number;
        $pdfReport->sourceDiscountPrice = $request->source_discount_value;
        $pdfReport->totalPrice = $request->invoice_price;
        $pdfReport->totalTaxes = $request->taxes_price;
        $pdfReport->totalPriceAfterTaxes = $request->total_price;

        return $pdfReport->RenderReport();
    }

    /**
     * Show the Index Page
     * @Any("test/preview", as="invoice.testPreview")
     */
    public function testPreview(Request $request) {
        $pdfReport = new InvoiceReport(TRUE);
        $index = count($request->invoiceItems) - 1;
        $invoiceItems = $request->invoiceItems;
        $sourceDiscountValue = 0;
        $taxesValue = 0;
        $has_source_discount = FALSE;
        $require_invoice = FALSE;
        foreach ($request->processes as $processId) {
            $clientProcess = ClientProcess::findOrFail($processId);
            if ($clientProcess->has_discount == TRUE) {
                $invoiceItems[++$index] = [
                    'size' => "",
                    'total_price' => $clientProcess->discount_value,
                    'class' => 'redColor',
                    'unit_price' => "",
                    'quantity' => "",
                    'description' => "خصم بسبب : " . $clientProcess->discount_reason,
                ];
            }
            if ($clientProcess->has_source_discount == TRUE) {
                $sourceDiscountValue += $clientProcess->source_discount_value;
                $has_source_discount = TRUE;
            }
            if ($clientProcess->require_invoice == TRUE) {
                $taxesValue += $clientProcess->taxes_value;
                $require_invoice = TRUE;
            }
        }
        if ($has_source_discount) {
            $invoiceItems[++$index] = [
                'size' => "",
                'total_price' => $sourceDiscountValue,
                'class' => 'redColor',
                'unit_price' => "",
                'quantity' => "",
                'description' => "خصم من المنبع",
            ];
        }
        if ($require_invoice) {
            $invoiceItems[++$index] = [
                'size' => "",
                'total_price' => $taxesValue,
                //'class' => 'redColor',
                'unit_price' => "",
                'quantity' => "",
                'description' => "قيمة الضريبة المضافة",
            ];
        }
        $client = Client::findOrFail($request->client_id);
        $pdfReport->clinetName = $client->name;
        $pdfReport->invoiceItems = $invoiceItems;
        $pdfReport->discountPrice = $request->discount_price;
        $pdfReport->discountReason = 'N\A';
        $pdfReport->invoiceDate = $request->invoice_date;
        $pdfReport->invoiceNo = '########';
        $pdfReport->sourceDiscountPrice = $request->source_discount_value;
        $pdfReport->totalPrice = $request->invoice_price;
        $pdfReport->totalTaxes = $request->taxes_price;
        $pdfReport->totalPriceAfterTaxes = $request->total_price;

        return $pdfReport->RenderTestReport();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        DB::beginTransaction();
        $all = $request->all();
        try {
            $all['invoice_number'] = Invoice::newInvoiceNumber();
            $invoice = Invoice::create($all);
            foreach ($request->invoiceItems as $item) {
                $item['invoice_id'] = $invoice->id;
                $invoiceItem = InvoiceItem::create($item);
            }
            foreach ($request->processes as $processId) {
                $clientProcess = ClientProcess::findOrFail($processId);
                $clientProcess->invoice_billed = ClientProcess::invoiceBilled;
                $clientProcess->invoice_id = $invoice->id;
                $clientProcess->save();
                if ($request->isPayNow) {
                    $clientProcess->payRemaining($invoice->invoice_number);
                }
            }
            $request->invoice_number = $invoice->invoice_number;
            DB::commit();
            $this->printPreview($request);
        } catch (\Exception $exc) {
            DB::rollBack();
            return back()->withInput()->with('error', $exc->getMessage());
        }
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
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice) {
        return view('invoice.edit', $this->setData($invoice));
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
