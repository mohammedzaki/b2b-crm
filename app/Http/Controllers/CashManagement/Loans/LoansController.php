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

namespace App\Http\Controllers\CashManagement\Loans;

use App\Http\Controllers\Controller;
use App\Models\Loans;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="loans")
 * @Resource("loans")
 * @Middleware({"web", "auth", "ability:admin,loans"})
 */
class LoansController extends Controller {

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    'name' => 'required|unique:loans,name,' . $id . '|min:5|max:255',
        ]);

        $validator->setAttributeNames([
            'name' => 'اسم القرض',
        ]);

        return $validator;
    }

    public function index() {
        $loans = Loans::all();
        return view('loans.index', compact('loans'));
    }

    public function create() {
        return view('loans.create');
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            Loans::create($request->all());
            return redirect()->route('loans.index')->with('success', 'تم اضافة قرض جديد.');
        }
    }

    public function edit($id) {
        $loans = Loans::findOrFail($id);
        return view('loans.edit', compact('loans'));
    }

    public function update(Request $request, $id) {
        $loans = Loans::findOrFail($id);

        $validator = $this->validator($request->all(), $loans->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $loans->update($request->all());
            return redirect()->back()->with('success', 'تم تعديل بيانات القرض.');
        }
    }
    
    public function destroy($id) {
        $loans = Loans::findOrFail($id);
        $loans->delete();

        return redirect()->back()->with('success', 'تم حذف قرض.');
    }

}
