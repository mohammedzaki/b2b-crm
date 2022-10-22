<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FacilityManagement\FacilityController;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\ClientProcessItem;
use App\Models\Employee;
use App\Models\FacilityTaxes;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="client-process")
 * @Resource("client-process", names={"index"="client.process.index", "create"="client.process.create", "store"="client.process.store", "show"="client.process.show", "edit"="client.process.edit", "update"="client.process.update", "destroy"="client.process.destroy"})
 * @Middleware({"web", "auth", "ability:admin,new-process-client"})
 */
class ClientProcessController extends Controller {

    protected function validator(array $data, $id = null)
    {
        $validator = Validator::make($data, [
                    'name'                => 'required|unique:client_processes,name,' . $id . '|min:5|max:255',
                    'client_id'           => 'required|exists:clients,id',
                    'employee_id'         => 'required|exists:employees,id',
                    //'notes' => 'string',
                    // FIXME: Solve validation 
                    //'has_discount' => 'boolean',
                    //'discount_value' => 'required_with:has_discount|numeric',
                    //'discount_reason' => 'required_with:has_discount|string',
                    //'require_invoice' => 'boolean',
                    'total_price'         => 'required|numeric',
                    'items.*.description' => 'required|string',
                    'items.*.quantity'    => 'required|numeric',
                    'items.*.unit_price'  => 'required|numeric',
                    'items.*.total_price' => 'numeric'
        ]);

        $validator->setAttributeNames([
            'name'                => 'اسم العملية',
            'client_id'           => 'اسم العميل',
            'employee_id'         => 'مشرف العملية',
            //'notes' => 'ملاحظات',
            'has_discount'        => 'الخصم',
            'discount_value'      => 'مبلغ الخصم',
            'discount_reason'     => 'سبب الخصم',
            'require_invoice'     => 'فاتورة',
            'items.*.description' => 'البيان',
            'items.*.quantity'    => 'الكمية',
            'items.*.unit_price'  => 'سعر الوحدة',
            'items.*.total_price' => 'القيمة'
        ]);

        return $validator;
    }

    public function index()
    {
        $processes = ClientProcess::all();
        return view('client.process.index', compact('processes'));
    }

    public function create()
    {
        $clients       = Client::select('id', 'name')->get();
        $employees     = Employee::select('id', 'name')->get();
        $clients_tmp   = [];
        $employees_tmp = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $clients    = $clients_tmp;
        $employees  = $employees_tmp;
        $taxesRates = null;
        return view('client.process.create', compact('clients', 'employees', 'taxesRates'));
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        $all       = $request->all();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get client info */
            $client                       = Client::find($request->client_id);
            /* get client all processes */
            $client_processes             = $client->processes;
            $total_opened_processes_price = 0;
            foreach ($client_processes as $client_process) {
                /* count opened process only */
                if ($client_process->status == "active") {
                    $total_opened_processes_price += $client_process->total_price;
                }
            }
            /* Can't create new process if client has exceeded the credit limit */
            // if($total_opened_processes_price >= $client->credit_limit){
            if ($client->credit_limit < ($total_opened_processes_price + $request->total_price)) {
                return redirect()->back()->withInput()->with('error', "خطأ في انشاء عملية جديدة، العميل " . $client->name . " قد تعدى الحد اﻻئتماني المسموح له.");
            } else {

                $all['status'] = ClientProcess::statusOpened;
                if (isset($request->require_invoice)) {
                    $all['require_invoice']  = TRUE;
                    $all['taxes_percentage'] = FacilityController::TaxesRate();
                } else {
                    $all['require_invoice'] = FALSE;
                }

                if (isset($request->has_discount)) {
                    $all['has_discount'] = TRUE;
                } else {
                    $all['has_discount'] = FALSE;
                }

                if (isset($request->has_source_discount)) {
                    $all['has_source_discount'] = TRUE;
                } else {
                    $all['has_source_discount'] = FALSE;
                }
                $clientProcess = ClientProcess::create($all);

                foreach ($all['items'] as $item) {
                    $item['process_id'] = $clientProcess->id;
                    ClientProcessItem::create($item);
                }
                return redirect()->route('client.process.index')->with('success', 'تم اضافة عملية جديدة.');
            }
        }
    }

    public function edit($id)
    {
        $process       = ClientProcess::findOrFail($id);
        $clients       = Client::select('id', 'name')->get();
        $employees     = Employee::select('id', 'name')->get();
        $clients_tmp   = [];
        $employees_tmp = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $clients    = $clients_tmp;
        $employees  = $employees_tmp;
        $taxesRates = FacilityTaxes::all()->mapWithKeys(function ($item) {
            return [$item['percentage'] => $item['percentage']];
        });
        return view('client.process.edit', compact('process', 'clients', 'employees', 'taxesRates'));
    }

    public function update(Request $request, $id)
    {
        $process      = ClientProcess::findOrFail($id);
        $all          = $request->all();
        $validator    = $this->validator($all, $process->id);
        $all['items'] = array_values($request->items);

        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get client info */
            $client                       = Client::find($request->client_id);
            /* get client all processes */
            $client_processes             = $client->processes;
            $total_opened_processes_price = 0;
            foreach ($client_processes as $client_process) {
                /* count opened process only */
                if ($client_process->status == "active" &&
                        $client_process->id != $process->id) {
                    $total_opened_processes_price += $client_process->total_price;
                }
            }
            /* Can't create new process if client has exceeded the credit limit */
            // if($total_opened_processes_price >= $client->credit_limit){
            if ($client->credit_limit < ($total_opened_processes_price + $request->total_price)) {
                return redirect()->back()->withInput($all)->with('error', "خطأ في انشاء عملية جديدة، العميل " . $client->name . " قد تعدى الحد اﻻئتماني المسموح له.");
            } else {
                if (isset($request->require_invoice)) {
                    $all['require_invoice']  = TRUE;
                    $all['taxes_percentage'] = FacilityController::TaxesRate();
                } else {
                    $all['require_invoice'] = FALSE;
                }

                if (isset($request->has_discount)) {
                    $all['has_discount'] = TRUE;
                } else {
                    $all['has_discount'] = FALSE;
                }

                if (isset($request->has_source_discount)) {
                    $all['has_source_discount'] = TRUE;
                } else {
                    $all['has_source_discount'] = FALSE;
                }

                $process->update($all);
                $process->checkProcessMustClosed();
                $items_ids = [];

                foreach ($all['items'] as $item) {
                    if (isset($item['id'])) {
                        $process_item = $process->items->where('id', intval($item['id']))->first();
                        $items_ids[]  = $item['id'];
                        $process_item->update($item);
                    } else {
                        $item['process_id'] = $process->id;
                        $pItem              = ClientProcessItem::create($item);
                        $items_ids[]        = $pItem->id;
                    }
                }
                /* delete others if exists */
                ClientProcessItem::where('process_id', $process->id)
                        ->whereNotIn('id', $items_ids)->forceDelete();
                if(isset($request->callback))
                    return redirect($request->callback);
                else
                    return redirect()->route('client.process.index')->with('success', 'تم تعديل بيانات عملية.');
            }
        }
    }

    public function destroy($id)
    {
        $process = ClientProcess::findOrFail($id);
        foreach ($process->items as $item) {
            $item->delete();
        }
        $process->delete();
        return redirect()->back()->with('success', 'تم حذف العملية.');
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("/trash", as="client.process.trash")
     */
    public function trash()
    {
        $processes = ClientProcess::onlyTrashed()->get();
        return view('client.process.trash', compact('processes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Get("/restore/{id}", as="client.process.restore")
     */
    public function restore($id)
    {
        ClientProcess::withTrashed()->find($id)->restore();
        ClientProcessItem::withTrashed()->where('process_id', $id)->restore();
        return redirect()->route('client.process.index')->with('success', 'تم استرجاع العملية.');
    }

}
