<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Supplier;
use App\User;
use Validator;

class SupplierController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,new-supplier');
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'name' => 'required|unique:suppliers,name,' . $id . '|min:5|max:255',
                    'address' => 'required|string',
                    'telephone' => 'digits:8',
                    'mobile' => 'required|digits:11',
                    'debit_limit' => 'required|numeric'
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم المورد',
            'address' => 'العنوان',
            'telephone' => 'التليفون',
            'mobile' => 'المحمول',
            'debit_limit' => 'حد المديونية'
        ]);

        return $validator;
    }

    public function index() {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    public function create() {
        return view('supplier.create');
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            Supplier::create($request->all());
            return redirect()->route('supplier.index')->with('success', 'تم اضافة مورد جديد.');
        }
    }

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id) {
        $supplier = Supplier::findOrFail($id);

        $validator = $this->validator($request->all(), $supplier->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $supplier->update($request->all());
            return redirect()->back()->with('success', 'تم تعديل بيانات المورد.');
        }
    }

    public function destroy($id) {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'تم حذف مورد.');
    }

}