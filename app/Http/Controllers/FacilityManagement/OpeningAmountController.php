<?php

namespace App\Http\Controllers\FacilityManagement;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\BankProfile;
use App\Models\Facility;
use App\Models\OpeningAmount;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="/facilityopeningamount")
 * @Resource("facilityopeningamount")
 * @Middleware({"web", "auth", "ability:admin,facility-info"})
 */
class OpeningAmountController extends Controller {

    protected function validator(array $data) {
        $validator = Validator::make($data, [
                    'reason' => 'required|max:255',
                    'amount' => 'required|numeric',
                    'save_id' => 'required',
        ]);

        $validator->setAttributeNames([
            'reason' => 'السبب',
            'amount' => 'قيمة الرصيد',
            'save_id' => 'اسم الحساب',
        ]);

        return $validator;
    }

    public function index() {
        $openingAmounts = OpeningAmount::all();
        $allBanks = BankProfile::allAsList();
        return view('facility.openingamount.index', compact('openingAmounts', 'allBanks'));
    }

    public function create() {
        $allBanks = BankProfile::allAsList(false, true, "حساب :");
        return view('facility.openingamount.create', compact('allBanks'));
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $all = $request->all();

            $openingAmount               = OpeningAmount::create($all);
            $openingAmount->deposit_date = DateTime::parse($request['deposit_date'])->format('Y-m-d');
            $openingAmount->save();
            $this->updateFacilityOpeningAmount();
            return redirect()->route('facilityopeningamount.index')->with('success', 'تم اضافة رصيد جديد.');
        }
    }

    public function edit($id) {
        $openingAmount = OpeningAmount::findOrFail($id);
        $allBanks = BankProfile::allAsList(false, true, "حساب :");
        return view('facility.openingamount.edit', compact('openingAmount', 'allBanks'));
    }

    public function update(Request $request, $id) {
        $openingAmount = OpeningAmount::findOrFail($id);
        $all           = $request->all();
        $validator     = $this->validator($all, $openingAmount->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $openingAmount->update($all);
            $openingAmount->deposit_date = DateTime::parse($request['deposit_date'])->format('Y-m-d');
            $openingAmount->save();
            $this->updateFacilityOpeningAmount();
            return redirect()->back()->with('success', 'تم تعديل بيانات الرصيد.');
        }
    }

    public function destroy($id) {
        $openingAmount = OpeningAmount::findOrFail($id);
        $openingAmount->delete();

        return redirect()->back()->with('success', 'تم حذف الرصيد.');
    }

    function updateFacilityOpeningAmount() {
        $facility                 = Facility::findOrFail(1);
        $facility->opening_amount = OpeningAmount::sum('amount');
        $facility->save();
    }

}
