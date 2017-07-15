<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\FacilityTaxes;
use Validator;
use App\Extensions\DateTime;

/**
 * @Controller(prefix="facility-taxes")
 * @Resource("facilityTaxes")
 * @Middleware("web")
 */
class FacilityTaxesController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('ability:admin,facility-info');
    }

    protected function validator(array $data) {
        $validator = Validator::make($data, [
                    
        ]);

        $validator->setAttributeNames([
        ]);

        return $validator;
    }

    public function index() {
        $facilityTaxes = FacilityTaxes::all();
        return view('facility.taxes.index', compact('facilityTaxes'));
    }

    public function create() {
        return view('facility.taxes.create');
    }

    public function store(Request $request) {
        $all = $request->all();
        $validator = $this->validator($all);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $facilityTaxes = FacilityTaxes::create($all);
            $this->updateFacilityTaxes($facilityTaxes);
            return redirect()->route('facilityTaxes.index')->with('success', 'تم اضافة ضريبة جديد.');
        }
    }

    public function edit($id) {
        $facilityTaxes = FacilityTaxes::findOrFail($id);

        return view('facility.taxes.edit', compact('facilityTaxes'));
    }

    public function update(Request $request, $id) {
        $facilityTaxes = FacilityTaxes::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $facilityTaxes->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $facilityTaxes->update($all);
            return redirect()->back()->with('success', 'تم تعديل بيانات الرصيد.');
        }
    }

    public function destroy($id) {
        $facilityTaxes = FacilityTaxes::findOrFail($id);
        $facilityTaxes->delete();

        return redirect()->back()->with('success', 'تم حذف الرصيد.');
    }

    function updateFacilityTaxes(FacilityTaxes $facilityTaxes) {
        $facility = Facility::findOrFail(1);
        $facility->sales_tax = $facilityTaxes->percentage;
        $prevFacilityTaxes = $facilityTaxes->getLast();
        $prevFacilityTaxes->enddate = DateTime::parse($facilityTaxes->changedate)->addDay(-1);
        $prevFacilityTaxes->save();
        $facility->save();
    }

}
