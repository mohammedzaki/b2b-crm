<?php

namespace App\Http\Controllers\CashManagement\Bank;

use App\Http\Controllers\Controller;
use App\Models\BankChequeBook;
use App\Models\BankProfile;
use Debugbar;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="/cheque-book/{bankId}")
 * @Resource("bank-profile.cheque-book")
 * @Middleware({"web", "auth", "ability:admin,bank-profile"})
 */
class ChequeBookController extends Controller
{

    public function create(BankProfile $bankProfile)
    {
        return view('bank-profile.cheque-book.create', compact('bankProfile'));
    }

    public function store(Request $request, BankProfile $bankProfile)
    {
        $all       = $request->all();
        $validator = $this->validator($all);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $bankChequeBook = $bankProfile->chequeBooks()->create($all);
            $bankChequeBook->save();
            return redirect()->route('bank-profile.edit', compact('bankProfile'))->with('success', 'تم حفظ دفتر الشيكات.');
        }
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'start_number' => 'required|numeric|greater_than:0',
            'end_number' => 'required|numeric|greater_than_field:start_number'
        ]);

        $validator->setAttributeNames([
            'start_number' => 'بداية الرقم التسلسلي',
            'end_number' => 'نهاية الرقم التسلسلي'
                                      ]);

        return $validator;
    }

    public function destroy($bankProfileId, $chequeBookId)
    {
        $bankProfile    = BankProfile::findOrFail($bankProfileId);
        $bankChequeBook = BankChequeBook::findOrFail($chequeBookId);
        if ($bankChequeBook->totalUsedNumbers() == 0) {
            $bankChequeBook->forceDelete();
            return redirect()->back()->with('success', 'تم حذف دفتر الشيكات.');
        } else {
            return redirect()->back()->with('error', 'لا يكمن حذف دفتر الشيكات لصرف شيكات منه.');
        }
    }

    public function edit($bankProfileId, $chequeBookId)
    {
        $bankProfile    = BankProfile::findOrFail($bankProfileId);
        $bankChequeBook = BankChequeBook::findOrFail($chequeBookId);
        if ($bankChequeBook->totalUsedNumbers() == 0) {
            return view('bank-profile.cheque-book.edit', compact('bankProfile', 'bankChequeBook'));
        } else {
            return redirect()->back()->with('error', 'لا يكمن تعديل دفتر الشيكات لصرف شيكات منه.');
        }
    }

    public function update(Request $request, $bankProfileId, $chequeBookId)
    {
        $bankProfile    = BankProfile::findOrFail($bankProfileId);
        $bankChequeBook = BankChequeBook::findOrFail($chequeBookId);
        $all            = $request->all();
        $validator      = $this->validator($all);
        if ($bankChequeBook->totalUsedNumbers() > 0) {
            return redirect()->back()->with('error', 'لا يكمن تعديل دفتر الشيكات لصرف شيكات منه.');
        }
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $bankChequeBook->update($all);
            return redirect()->route('bank-profile.edit', compact('bankProfile'))->with('success', 'تم تعديل دفتر الشيكات.');
        }
    }

}
