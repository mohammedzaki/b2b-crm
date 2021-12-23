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

namespace App\Http\Controllers\CashManagement\Bank;

use App\Models\BankProfile;
use Illuminate\Http\Request;
use Validator;

/**
 * Description of ProfileController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="bank-profile")
 * @Resource("bank-profile")
 * @Middleware({"web", "auth", "ability:admin,bank-profile"})
 */
class BankProfileController
{

    protected function validator(array $data, $id = null)
    {
        $validator = Validator::make($data, [
            'name'             => 'required|unique:bank_profiles,name,' . $id . '|min:5|max:255',
            'statement_number' => 'required',
            'branch_address'   => 'required'
        ]);

        $validator->setAttributeNames([
                                          'name'             => 'اسم البنك',
                                          'statement_number' => 'رقم الحساب',
                                          'branch_address'   => 'عنوان الفرع'
                                      ]);

        return $validator;
    }

    public function index()
    {
        $bankProfiles = BankProfile::all();
        return view('bank-profile.index', compact('bankProfiles'));
    }

    public function create()
    {
        return view('bank-profile.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            BankProfile::create($request->all());
            return redirect()->route('bank-profile.index')->with('success', 'تم اضافة بنك جديد.');
        }
    }

    public function edit($id)
    {
        $bankProfile = BankProfile::with('chequeBooks')->findOrFail($id);
        return view('bank-profile.edit', compact('bankProfile'));
    }

    public function update(Request $request, $id)
    {
        $bankProfile = BankProfile::findOrFail($id);

        $validator = $this->validator($request->all(), $bankProfile->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $bankProfile->update($request->all());
            return redirect()->back()->with('success', 'تم تعديل بيانات البنك.');
        }
    }

    public function destroy($id)
    {
        $bankProfile = BankProfile::findOrFail($id);
        $bankProfile->delete();

        return redirect()->back()->with('success', 'تم حذف بنك.');
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("/trash", as="bank-profile.trash")
     */
    public function trash()
    {
        $bankProfiles = BankProfile::onlyTrashed()->get();
        return view('bank-profile.trash', compact('bankProfiles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @Get("/restore/{id}", as="bank-profile.restore")
     */
    public function restore($id)
    {
        BankProfile::withTrashed()->find($id)->restore();
        return redirect()->route('bank-profile.index')->with('success', 'تم استرجاع البنك.');
    }
}
