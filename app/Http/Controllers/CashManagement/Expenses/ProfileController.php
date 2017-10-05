<?php

/*
 * B2B CRM Software License
 *
 * Copyright (C) ZakiSoft ltd - All Rights Reserved.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Mohammed Zaki mohammedzaki.dev@gmail.com, September 2017
 */

namespace App\Http\Controllers\CashManagement\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="expenses")
 * @Resource("expenses")
 * @Middleware({"web", "auth"})
 */
class ProfileController extends Controller {

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
