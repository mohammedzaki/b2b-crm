<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use App\Models\SupplierProcessItem;
use App\Models\FacilityTaxes;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="supplier-process")
 * @Resource("supplier-process", names={"index"="supplier.process.index", "create"="supplier.process.create", "store"="supplier.process.store", "show"="supplier.process.show", "edit"="supplier.process.edit", "update"="supplier.process.update", "destroy"="supplier.process.destroy"})
 * @Middleware({"web", "auth", "ability:admin,new-process-supplier"})
 */
class SupplierProcessController extends Controller {

    protected function validator(array $data, $id = null)
    {
        $validator = Validator::make($data, [
                    //'name' => 'required|unique:client_processes,name,' . $id . '|min:5|max:255',
                    'client_id'           => 'required|exists:clients,id',
                    'client_process_id'   => 'required|exists:client_processes,id',
                    'supplier_id'         => 'required|exists:suppliers,id',
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
            //'name' => 'اسم العملية',
            'client_id'           => 'اسم العميل',
            'client_process_id'   => 'اسم العملية',
            'supplier_id'         => 'اسم المورد',
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
        $processes = SupplierProcess::all();
        return view('supplier.process.index', compact('processes'));
    }

    public function create()
    {
        $suppliers           = Supplier::select('id', 'name')->get();
        $clients             = Client::allHasOpenProcess();
        $employees           = Employee::select('id', 'name')->get();
        $clientProcesses     = ClientProcess::allOpened()->get();
        $suppliers_tmp       = [];
        $employees_tmp       = [];
        $clients_tmp         = [];
        $clientProcesses_tmp = [];
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($clientProcesses as $process) {
            $clientProcesses_tmp[$process->id]['name']      = $process->name;
            $clientProcesses_tmp[$process->id]['client_id'] = $process->client->id;
        }
        $suppliers       = $suppliers_tmp;
        $employees       = $employees_tmp;
        $clients         = $clients_tmp;
        $clientProcesses = $clientProcesses_tmp;
        $taxesRates      = null;
        return view('supplier.process.create', compact('suppliers', 'employees', 'clients', 'clientProcesses', 'taxesRates'));
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        $all       = $request->all();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $sup = SupplierProcess::where([
                        ['client_process_id', '=', $request->client_process_id],
                        ['supplier_id', '=', $request->supplier_id]
                    ])->first();
            if (!empty($sup)) {
                return redirect()->back()->withInput()->with('error', 'لا يمكن اختيار نفس المورد لنفس العملية');
            }
            /* get supplier info */
            $supplier                     = Supplier::find($request->supplier_id);
            /* get supplier all processes */
            $supplier_processes           = $supplier->processes;
            $total_opened_processes_price = 0;

            $all['status'] = SupplierProcess::statusOpened;
            if (isset($request->require_invoice)) {
                $all['require_invoice'] = TRUE;
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

            $all['name']     = ClientProcess::findOrFail($all['client_process_id'])->name;
            $supplierProcess = SupplierProcess::create($all);

            foreach ($all['items'] as $item) {
                $item['process_id'] = $supplierProcess->id;
                SupplierProcessItem::create($item);
            }

            return redirect()->route('supplier.process.index')->with('success', 'تم اضافة عملية جديدة.');
        }
    }

    public function edit($id)
    {
        $process             = SupplierProcess::findOrFail($id);
        $suppliers           = Supplier::select('id', 'name')->get();
        $clients             = Client::select('id', 'name')->get();
        $employees           = Employee::select('id', 'name')->get();
        $clientProcesses     = ClientProcess::all();
        $suppliers_tmp       = [];
        $employees_tmp       = [];
        $clients_tmp         = [];
        $clientProcesses_tmp = [];
        foreach ($suppliers as $supplier) {
            $suppliers_tmp[$supplier->id] = $supplier->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($clientProcesses as $clientProcess) {
            $clientProcesses_tmp[$clientProcess->id]['name']      = $clientProcess->name;
            $clientProcesses_tmp[$clientProcess->id]['client_id'] = $clientProcess->client->id;
        }
        $suppliers          = $suppliers_tmp;
        $employees          = $employees_tmp;
        $clients            = $clients_tmp;
        $clientProcesses    = $clientProcesses_tmp;
        $process->client_id = $process->clientProcess->client_id;
        $taxesRates         = FacilityTaxes::all()->mapWithKeys(function ($item) {
            return [$item['percentage'] => $item['percentage']];
        });
        return view('supplier.process.edit', compact('process', 'suppliers', 'employees', 'clients', 'clientProcesses', 'taxesRates'));
    }

    public function update(Request $request, $id)
    {
        $process      = SupplierProcess::findOrFail($id);
        $all          = $request->all();
        $validator    = $this->validator($all, $process->id);
        $all['items'] = array_values($request->items);

        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get supplier info */
            $supplier                     = Supplier::find($request->supplier_id);
            /* get supplier all processes */
            $supplier_processes           = $supplier->processes;
            $total_opened_processes_price = 0;
            if (isset($request->require_invoice)) {
                $all['require_invoice'] = TRUE;
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

            $all['name'] = ClientProcess::findOrFail($all['client_process_id'])->name;
            $process->update($all);
            $process->checkProcessMustClosed();
            $items_ids   = [];

            foreach ($all['items'] as $item) {
                if (isset($item['id'])) {
                    $process_item = $process->items->where('id', intval($item['id']))->first();
                    $items_ids[]  = $item['id'];
                    $process_item->update($item);
                } else {
                    $item['process_id'] = $process->id;
                    $pItem              = SupplierProcessItem::create($item);
                    $items_ids[]        = $pItem->id;
                }
            }
            /* delete others if exists */
            SupplierProcessItem::where('process_id', $process->id)
                ->whereNotIn('id', $items_ids)->forceDelete();
            if(isset($request->callback))
                return redirect($request->callback);
            else
                return redirect()->route('supplier.process.index')->with('success', 'تم تعديل بيانات عملية.');
        }
    }

    public function destroy($id)
    {
        $process = SupplierProcess::findOrFail($id);
        foreach ($process->items as $item) {
            $item->delete();
        }
        $process->delete();
        return redirect()->back()->with('success', 'تم حذف العملية.');
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("/trash", as="supplier.process.trash")
     */
    public function trash()
    {
        $processes = SupplierProcess::onlyTrashed()->get();
        return view('supplier.process.trash', compact('processes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Get("/restore/{id}", as="supplier.process.restore")
     */
    public function restore($id)
    {
        SupplierProcess::withTrashed()->find($id)->restore();
        SupplierProcessItem::withTrashed()->where('process_id', $id)->restore();
        return redirect()->route('supplier.process.index')->with('success', 'تم استرجاع العملية.');
    }

}
