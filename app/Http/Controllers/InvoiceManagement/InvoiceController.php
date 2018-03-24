<?php

namespace App\Http\Controllers\InvoiceManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FacilityManagement\FacilityController;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Reports\Invoice\Invoice as InvoiceReport;
use Illuminate\Http\Request;
use DB;
use Exception;

/**
 * @Controller(prefix="invoice")
 * @Resource("invoice")
 * @Middleware("web")
 */
class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        for ($index = 0; $index < count($invoices); $index++) {
            $invoices[$index]->processesNames = '(' . implode('), (', $invoices[$index]->processes()->pluck('name')->toArray()) . ')';
        }
        return view('invoice.index', compact('invoices'));
    }

    private function setData(Invoice $invoice = NULL)
    {
        $clients = [];
        if ($invoice) {
            $clients = Client::allHasInvoiceProcess();
        } else {
            $clients = Client::allHasUnBilledInvoiceProcess();
        }
        $clients_tmp      = [];
        $clientProcesses  = [];
        $invoiceProcesses = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
            $invoiceProcesses         = $client->unInvoiceProcesses;
            if ($invoice && !$invoice->isLastInvoice()) {
                $invoiceProcesses = [];
            }
            foreach ($invoiceProcesses as $process) {
                $clientProcesses[$client->id][$process->id] = [
                    'name'            => $process->name,
                    'totalPrice'      => $process->total_price,
                    'totalPriceTaxes' => $process->total_price_taxes,
                    'discount'        => ($process->discount_value ? $process->discount_value : 0),
                    'sourceDiscount'  => ($process->source_discount_value ? $process->source_discount_value : 0),
                    'taxes'           => ($process->taxes_value ? $process->taxes_value : 0),
                    'status'          => $process->status,
                    'items'           => $process->items,
                    'totalPaid'       => $process->totalDeposits(),
                    'totalRemaining'  => $process->totalRemaining(),
                    'invoice_id'      => $process->invoice_id
                ];
            }
        }
        if ($invoice && $invoice->isLastInvoice()) {
            $invoiceProcesses = $invoice->processes;
            foreach ($invoiceProcesses as $process) {
                $clientProcesses[$invoice->client_id][$process->id] = [
                    'name'            => $process->name,
                    'totalPrice'      => $process->total_price,
                    'totalPriceTaxes' => $process->total_price_taxes,
                    'discount'        => ($process->discount_value ? $process->discount_value : 0),
                    'sourceDiscount'  => ($process->source_discount_value ? $process->source_discount_value : 0),
                    'taxes'           => ($process->taxes_value ? $process->taxes_value : 0),
                    'status'          => $process->status,
                    'items'           => $process->items,
                    'totalPaid'       => $process->totalDeposits(),
                    'totalRemaining'  => $process->totalRemaining(),
                    'invoice_id'      => $process->invoice_id
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
    public function create()
    {
        return view('invoice.create', $this->setData());
    }

    /**
     * Display the specified resource.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return $this->showInvoicePreview($invoice, FALSE);
    }

    /**
     * Show the Index Page
     * @Post("preview", as="invoice.preview")
     * @Post("{invoice}/preview", as="invoice.editPreview")
     */
    public function preview(Request $request)
    {
        $all                = $request->all();
        $invoice            = new Invoice;
        $invoice->fill($all);
        $invoice->items     = $request->invoiceItems;
        $invoice->processes = $request->processes;
        session([
            'invoiceObj' => $invoice
        ]);
        return $this->showInvoicePreview($invoice);
    }

    private function showInvoicePreview(Invoice $invoice, $isPreview = TRUE)
    {
        $pdfReport = $this->getReport($invoice, $isPreview);
        return $pdfReport->preview();
    }

    private function getReport(Invoice $invoice, $isPreview = TRUE)
    {
        $pdfReport           = new InvoiceReport(TRUE);
        $index               = count($invoice->items) - 1;
        $invoiceItems        = $invoice->items;
        $has_source_discount = FALSE;
        foreach ($invoice->processes as $clientProcess) {
            if ($isPreview) {
                $clientProcess = ClientProcess::findOrFail($clientProcess);
            }
            if ($clientProcess->has_discount == TRUE) {
                $invoiceItems[++$index] = [
                    'size'        => "",
                    'total_price' => $clientProcess->discount_value,
                    'class'       => 'redColor',
                    'unit_price'  => "",
                    'quantity'    => "",
                    'description' => "خصم بسبب : " . $clientProcess->discount_reason,
                ];
            }
            if ($clientProcess->has_source_discount == TRUE) {
                $has_source_discount = TRUE;
            }
        }
        $invoiceItems[++$index] = [
            'size'        => '',
            'total_price' => '',
            'class'       => '',
            'unit_price'  => '',
            'quantity'    => '',
            'description' => '',
        ];
        $invoiceItems[++$index] = [
            'size'        => "",
            'total_price' => $invoice->invoice_price,
            'class'       => '',
            'unit_price'  => "",
            'quantity'    => "",
            'description' => "الاجمالى قبل الضريبة",
        ];
        $taxes_percentage       = FacilityController::TaxesRate($invoice->invoice_date);
        $invoiceItems[++$index] = [
            'size'        => "",
            'total_price' => $invoice->taxes_price,
            'class'       => '',
            'unit_price'  => "",
            'quantity'    => "",
            'description' => "قيمة الضريبة المضافة {$taxes_percentage}%",
        ];
        if ($has_source_discount) {
            $invoiceItems[++$index] = [
                'size'        => "",
                'total_price' => $invoice->source_discount_value,
                'class'       => 'redColor',
                'unit_price'  => "",
                'quantity'    => "",
                'description' => "(-) خصم من المنبع",
            ];
        }
        if ($isPreview) {
            $pdfReport->invoiceId = 'preview';
        } else {
            $pdfReport->invoiceId = $invoice->id;
        }
        $pdfReport->clinetName           = $invoice->client->name;
        $pdfReport->invoiceItems         = $invoiceItems;
        $pdfReport->discountPrice        = $invoice->discount_price;
        $pdfReport->discountReason       = 'N\A';
        $pdfReport->invoiceDate          = $invoice->invoice_date;
        $pdfReport->invoiceNo            = $invoice->invoice_number;
        $pdfReport->sourceDiscountPrice  = $invoice->source_discount_value;
        $pdfReport->totalPrice           = $invoice->invoice_price;
        $pdfReport->totalTaxes           = $invoice->taxes_price;
        $pdfReport->totalPriceAfterTaxes = $invoice->total_price;
        return $pdfReport;
    }

    /**
     * Show the Index Page
     * @Get("{invoice}/printPreviewPDF", as="invoice.printPreviewPDF")
     */
    public function printPreviewPDF($invoice, Request $request)
    {
        if($invoice == 'preview') {
            $invoice = session('invoiceObj');
        $pdfReport = $this->getReport($invoice);
        } else {
            $invoice = Invoice::findOrFail($invoice);
        $pdfReport = $this->getReport($invoice, FALSE);
        }
        if (isset($request->withLetterHead)) {
            $pdfReport->withLetterHead = TRUE;
        } else {
            $pdfReport->withLetterHead = FALSE;
        }
        return $pdfReport->exportPDF();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->invoice_date < Invoice::getLastInvoiceDate()) {
            return redirect()->back()->withInput()->with('error', 'التاريخ يجب ان يكون اكبر الفاتورة السابقة');
        }
        DB::beginTransaction();
        $all = $request->all();
        try {
            $all['invoice_number'] = Invoice::newInvoiceNumber();
            $invoice               = Invoice::create($all);
            foreach ($request->invoiceItems as $item) {
                $item['invoice_id'] = $invoice->id;
                $invoiceItem        = InvoiceItem::create($item);
            }
            foreach ($request->processes as $processId) {
                $clientProcess                 = ClientProcess::findOrFail($processId);
                $clientProcess->invoice_billed = ClientProcess::invoiceBilled;
                $clientProcess->invoice_id     = $invoice->id;
                $clientProcess->save();
                if ($request->isPayNow) {
                    $clientProcess->payRemaining($invoice->invoice_number);
                }
            }
            $request->invoice_number = $invoice->invoice_number;
            DB::commit();
            return redirect()->route('invoice.index')->with('success', 'تم اضافة الفاتورة');
        } catch (Exception $exc) {
            DB::rollBack();
            return back()->withInput()->with('error', $exc->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        return view('invoice.edit', $this->setData($invoice));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //echo "{$invoice->getPrevInvoiceDate()}, {$invoice->getNextInvoiceDate()}, {$request->invoice_date}";
        $all = $request->all();
        if ($request->invoice_date >= $invoice->getPrevInvoiceDate() && $request->invoice_date <= $invoice->getNextInvoiceDate()) {
            $invoice->update($all);
            $items_ids = [];
            if ($invoice->isLastInvoice()) {
                foreach ($request->processes as $processId) {
                    $clientProcess                 = ClientProcess::findOrFail($processId);
                    $clientProcess->invoice_billed = ClientProcess::invoiceBilled;
                    $clientProcess->invoice_id     = $invoice->id;
                    $clientProcess->save();
                    $items_ids[]                   = $clientProcess->id;
                }
                ClientProcess::where('invoice_id', $invoice->id)
                        ->whereNotIn('id', $items_ids)
                        ->update(['invoice_id' => 0, 'invoice_billed' => ClientProcess::invoiceUnBilled]);
            }
            $items_ids = [];
            foreach ($all['invoiceItems'] as $item) {
                if (isset($item['id'])) {
                    $invoiceOldItem = InvoiceItem::findOrFail($item['id']);
                    $invoiceOldItem->update($item);
                    $items_ids[]    = $item['id'];
                } else {
                    $item['invoice_id'] = $invoice->id;
                    $invoiceNewItem     = InvoiceItem::create($item);
                    $items_ids[]        = $invoiceNewItem->id;
                }
            }
            InvoiceItem::where('invoice_id', $invoice->id)
                    ->whereNotIn('id', $items_ids)->forceDelete();
            return redirect()->route('invoice.index')->with('success', 'تم تعديل بيانات الفاتورة.');
        } else {
            return redirect()->back()->withInput()->with('error', 'التاريخ يجب ان يكون اكبر الفاتورة السابقة و اقل من التالية');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        ClientProcess::where('invoice_id', $invoice->id)
                ->update(['invoice_id' => nullValue(), 'invoice_billed' => ClientProcess::invoiceUnBilled]);
        InvoiceItem::where('invoice_id', $invoice->id)->forceDelete();
        $invoice->forceDelete();
        return redirect()->back()->with('success', 'تم حذف الفاتورة.');
    }

    /**
     * Pay invoice.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     * @Get("{invoice}/pay", as="invoice.pay")
     */
    public function payInvoice(Invoice $invoice)
    {
        $invoice->pay();
        return redirect()->back()->with('success', 'تم تحصيل الفاتورة.');
    }

}
