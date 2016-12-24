<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Client;
use App\Employee;
use App\ClientProcess;
use App\ClientProcessItem;
use Validator;

class ClientProcessController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,new-process-client');
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'name' => 'required|unique:supplier_processes,name,' . $id . '|min:5|max:255',
                    'client_id' => 'required|exists:clients,id',
                    'employee_id' => 'required|exists:employees,user_id',
                    'notes' => 'string',
                    'has_discount' => 'boolean',
                    'discount_percentage' => 'required_with:has_discount|numeric',
                    'discount_reason' => 'required_with:has_discount|string',
                    'require_bill' => 'boolean',
                    'total_price' => 'required|numeric',
                    'items.*.description' => 'required|string',
                    'items.*.quantity' => 'required|numeric',
                    'items.*.unit_price' => 'required|numeric',
                    'items.*.total_price' => 'numeric'
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم العملية',
            'client_id' => 'اسم العميل',
            'employee_id' => 'مشرف العملية',
            'notes' => 'مﻻحظات',
            'has_discount' => 'الخصم',
            'discount_percentage' => 'نسبة الخصم',
            'discount_reason' => 'سبب الخصم',
            'require_bill' => 'فاتورة',
            'items.*.description' => 'البيان',
            'items.*.quantity' => 'الكمية',
            'items.*.unit_price' => 'سعر الوحدة',
            'items.*.total_price' => 'القيمة'
        ]);

        return $validator;
    }

    public function index() {
        $processes = ClientProcess::all();
        return view('client.process.index', compact('processes'));
    }

    public function create() {
        $clients = Client::select('id', 'name')->get();
        $employees = Employee::select('user_id', 'name')->get();
        $clients_tmp = [];
        $employees_tmp = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->user_id] = $employee->name;
        }
        $clients = $clients_tmp;
        $employees = $employees_tmp;
        return view('client.process.create', compact(['clients', 'employees']));
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get client info */
            $client = Client::find($request->client_id);
            /* get client all processes */
            $client_processes = $client->processes;
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
                return redirect()->back()->withInput()->with('error', "خطأ في انشاء عملية جديدة، العميل " . $client->name . " قد تعدى الحد اﻻئتماني المسموح له."
                );
            } else {

                $all['status'] = 'active';
                $clientProcess = ClientProcess::create($all);

                foreach ($all['items'] as $item) {
                    $item['process_id'] = $clientProcess->id;
                    ClientProcessItem::create($item);
                }
                return redirect()->route('client.process.index')->with('success', 'تم اضافة عملية جديدة.');
            }
        }
    }

    public function edit($id) {
        $process = ClientProcess::findOrFail($id);
        $clients = Client::select('id', 'name')->get();
        $employees = Employee::select('user_id', 'name')->get();
        $clients_tmp = [];
        $employees_tmp = [];
        foreach ($clients as $client) {
            $clients_tmp[$client->id] = $client->name;
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->user_id] = $employee->name;
        }
        $clients = $clients_tmp;
        $employees = $employees_tmp;
        return view('client.process.edit', compact(['process', 'clients', 'employees']));
    }

    public function update(Request $request, $id) {
        $process = ClientProcess::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $process->id);
        $all['items'] = array_values($request->items);

        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* get client info */
            $client = Client::find($request->client_id);
            /* get client all processes */
            $client_processes = $client->processes;
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
                return redirect()->back()->withInput($all)->with('error', "خطأ في انشاء عملية جديدة، العميل " . $client->name . " قد تعدى الحد اﻻئتماني المسموح له."
                );
            } else {
                $process->update($all);
                $items_ids = [];

                foreach ($all['items'] as $item) {
                    if (isset($item['id'])) {
                        $process_item = $process->items->where('id', intval($item['id']))->first();
                        $items_ids[] = $item['id'];
                        $process_item->update($item);
                    } else {
                        $item['process_id'] = $process->id;
                        $pItem = ClientProcessItem::create($item);
                        $items_ids[] = $pItem->id;
                    }
                }
                /* delete others if exists */
                ClientProcessItem::where('process_id', $process->id)
                        ->whereNotIn('id', $items_ids)->forceDelete();

                return redirect()->route('client.process.index')->with('success', 'تم تعديل بيانات عملية.');
            }
        }
    }

    public function destroy($id) {
        $process = ClientProcess::findOrFail($id);
        foreach ($process->items as $item) {
            $item->delete();
        }
        $process->delete();
        return redirect()->back()->with('success', 'تم حذف العملية.');
    }

    public function trash() {
        $processes = ClientProcess::onlyTrashed()->get();
        return view('client.process.trash', compact('processes'));
    }

    public function restore($id) {
        ClientProcess::withTrashed()->find($id)->restore();
        ClientProcessItem::withTrashed()->where('process_id', $id)->restore();
        return redirect()->route('client.process.index')->with('success', 'تم استرجاع العملية.');
    }

}
