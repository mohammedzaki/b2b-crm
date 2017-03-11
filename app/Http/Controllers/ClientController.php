<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Client;
use App\Models\AuthorizedPerson;
use App\Models\User;
use App\Models\Employee;
use Validator;

class ClientController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,new-client');
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'name' => 'required|unique:clients,name,' . $id . '|string',
                    'address' => 'required|string',
                    'telephone' => 'digits_between:8,14',
                    'mobile' => 'required|numeric',
                    //FIXME: this valdiation
                    //'referral' => 'required_without:is_client_company|exists:users,username',
                    //'referral_percentage' => 'required_without:is_client_company|required_with:referral|numeric',
                    'credit_limit' => 'required|numeric',
                    'is_client_company' => 'boolean',
                    'authorized.*.name' => 'required|string',
                    'authorized.*.jobtitle' => 'required|string',
                    'authorized.*.telephone' => 'required|digits_between:8,14',
                    'authorized.*.email' => 'required|email'
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم العميل',
            'address' => 'العنوان',
            'telephone' => 'التليفون',
            'mobile' => 'المحمول',
            //'referral' => 'اسم المندوب',
            //'referral_percentage' => 'نسبة العمولة',
            'credit_limit' => 'الحد اﻻئتماني',
            'is_client_company' => 'عميل شركة',
            'authorized.*.name' => 'اسم الشخص المفوض',
            'authorized.*.jobtitle' => 'المسمى الوظيفي',
            'authorized.*.telephone' => 'التليفون',
            'authorized.*.email' => 'البريد اﻻلكتروني'
        ]);

        return $validator;
    }

    public function index() {
        $clients = Client::all();
        return view('client.index', compact('clients'));
    }

    public function create() {
        $employees = Employee::select('id', 'name')->get();
        $employees_tmp[-1] = "اختر اسم المندوب";
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $employees = $employees_tmp;
        return view('client.create', compact('employees'));
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $all = $request->all();

            if ($request->is_client_company) {
                $all['referral_id'] = null;
                $all['referral_percentage'] = null;
            } else {
                $all['is_client_company'] = false;
            }

            $client = Client::create($all);
            if ($request->authorized) {
                for ($i = 0; $i < count($request->authorized); $i++) {
                    $all['authorized'][$i]['client_id'] = $client->id;
                }
                AuthorizedPerson::insert($all['authorized']);
            }

            return redirect()->route('client.index')->with('success', 'تم اضافة عميل جديد.');
        }
    }

    public function edit($id) {
        $client = Client::findOrFail($id);
        $authorized = AuthorizedPerson::where('client_id', $client->id)->get()->toArray();
        $employees = Employee::select('id', 'name')->get();
        $employees_tmp[-1] = "اختر اسم المندوب";
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $employees = $employees_tmp;
        return view('client.edit', compact('client', 'authorized', 'employees'));
    }

    public function update(Request $request, $id) {
        $client = Client::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $client->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            /*             * ****************************************** */
            if ($request->is_client_company) {
                $all['referral_id'] = null;
                $all['referral_percentage'] = null;
            } else {
                $all['is_client_company'] = false;
            }
            /*             * ****************************************** */
            $client->update($all);

            if ($request->authorized) {
                //recalculate array index
                $authorized_request = array_values($all['authorized']);
                $authorized_ids = [];

                for ($i = 0; $i < count($authorized_request); $i++) {
                    /* if request does not have id, it means it's new one */
                    if (isset($authorized_request[$i]['id'])) {
                        /* update record */
                        $person = AuthorizedPerson::findOrFail(
                                        $authorized_request[$i]['id']
                        );

                        $authorized_ids[] = $authorized_request[$i]['id'];

                        $person->update($authorized_request[$i]);
                    } else {
                        /* create new record */
                        /* add client id */
                        $authorized_request[$i]['client_id'] = $client->id;
                        $person = AuthorizedPerson::create($authorized_request[$i]);
                        $authorized_ids[] = $person->id;
                    }
                }
                /* delete others if exists */
                AuthorizedPerson::where('client_id', $client->id)
                        ->whereNotIn('id', $authorized_ids)->delete();
            } else {
                /* delete all */
                AuthorizedPerson::where('client_id', $client->id)->delete();
            }

            return redirect()->back()->with('success', 'تم تعديل بيانات العميل.');
        }
    }

    public function destroy($id) {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->back()->with('success', 'تم حذف عميل.');
    }

    public function trash() {
        $clients = Client::onlyTrashed()->get();
        return view('client.trash', compact('clients'));
    }

    public function restore($id) {
        Client::withTrashed()->find($id)->restore();
        return redirect()->route('client.index')->with('success', 'تم استرجاع عميل جديد.');
    }

}
