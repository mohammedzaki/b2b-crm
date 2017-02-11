<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Expenses;
use App\User;
use Validator;

class ExpensesController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,new-supplier');
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
            'name' => 'required|unique:expenses,name,' . $id . '|min:5|max:255',
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم المصروف',
        ]);

        return $validator;
    }

    public function index() {
        $expenses = Expenses::all();
        return view('expenses.index', compact('expenses'));
    }

    public function create() {
        return view('expenses.create');
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            Expenses::create($request->all());
            return redirect()->route('expenses.index')->with('success', 'تم اضافة مصروف جديد.');
        }
    }

    public function edit($id) {
        $expenses = Expenses::findOrFail($id);
        return view('expenses.edit', compact('expenses'));
    }

    public function update(Request $request, $id) {
        $expenses = Expenses::findOrFail($id);

        $validator = $this->validator($request->all(), $expenses->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $expenses->update($request->all());
            return redirect()->back()->with('success', 'تم تعديل بيانات المصروف.');
        }
    }
    // FIXME: must be softDelete
    public function destroy($id) {
        $expenses = Expenses::findOrFail($id);
        $expenses->delete();

        return redirect()->back()->with('success', 'تم حذف مصروف.');
    }
}
