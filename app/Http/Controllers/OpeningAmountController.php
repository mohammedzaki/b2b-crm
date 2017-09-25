<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\OpeningAmount;
use Validator;
use App\Extensions\DateTime;

/**
 * @Controller(prefix="/facilityopeningamount")
 * @Resource("facilityopeningamount")
 * @Middleware({"web", "auth", "ability:admin,facility-info"})
 */
class OpeningAmountController extends Controller {

    protected function validator(array $data) {
        $validator = Validator::make($data, [
                    'reason' => 'max:255',
                    'amount' => 'numeric',
        ]);

        $validator->setAttributeNames([
            'reason' => 'السبب',
            'amount' => 'قيمة الرصيد',
        ]);

        return $validator;
    }

    public function index() {
        $openingAmounts = OpeningAmount::all();
        return view('facility.openingamount.index', compact('openingAmounts'));
    }

    public function create() {
        return view('facility.openingamount.create');
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            $all = $request->all();

            $openingAmount               = OpeningAmount::create($all);
            $openingAmount->deposit_date = DateTime::parse($request['depositdate'])->format('Y-m-d');
            $openingAmount->save();
            $this->updateFacilityOpeningAmount();
            return redirect()->route('facilityopeningamount.index')->with('success', 'تم اضافة رصيد جديد.');
        }
    }

    public function edit($id) {
        $openingAmount = OpeningAmount::findOrFail($id);

        return view('facility.openingamount.edit', compact('openingAmount'));
    }

    public function update(Request $request, $id) {
        $openingAmount = OpeningAmount::findOrFail($id);
        $all           = $request->all();
        $validator     = $this->validator($all, $openingAmount->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $openingAmount->update($all);
            $openingAmount->deposit_date = DateTime::parse($request['depositdate'])->format('Y-m-d');
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
