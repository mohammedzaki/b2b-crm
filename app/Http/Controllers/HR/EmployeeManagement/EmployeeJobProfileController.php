<?php

namespace App\Http\Controllers\HR\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeJobProfile;
use App\Models\User;
use App\Extensions\DateTime;
use Illuminate\Http\Request;
use Validator;

/**
 * @Controller(prefix="/employeeJobProfile/{employeeId}")
 * @Resource("employee.employeeJobProfile")
 * @Middleware({"web", "auth", "ability:admin,employees-permissions"})
 */
class EmployeeJobProfileController extends Controller
{

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
        ]);

        $validator->setAttributeNames([
        ]);

        return $validator;
    }

    public function index(Employee $employee)
    {
        $employeeJobProfiles = $employee->jobProfiles;
        return view('employee.job-profile.index', compact('employeeJobProfiles'));
    }

    public function create(Employee $employee)
    {
        return view('employee.job-profile.create', compact('employee'));
    }

    public function store(Request $request, Employee $employee)
    {
        $all       = $request->all();
        $validator = $this->validator($all);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            // end last job
            if ($employee->currentJobProfile != null && !empty($employee->currentJobProfile)) {
                $oldJob = $employee->currentJobProfile;
                $oldDate = DateTime::parse($oldJob->start_date);
                $newDate = DateTime::parse($all['start_date']);
                if ($oldDate > $newDate) {
                    return redirect()->back()->withInput()->with('error', 'تاريخ التعديل يجب ان يكون اكبر من اخر تاريخ انتهاء لاخر راتب.');
                }
                $oldJob->end_date = DateTime::parse($all['start_date'])->addDay(-1);
                $oldJob->save();
            }
            $employeeJobProfile       = $employee->jobProfiles()->create($all);
            $employee->current_job_id = $employeeJobProfile->id;
            $employee->save();
            return redirect()->route('employee.edit', compact('employee'))->with('success', 'تم تعدبل راتب الموظف.');
        }
    }

    public function destroy(Employee $employee, $id)
    {
        return redirect()->back()->with('success', 'تم حذف موظف.');
    }

    public function edit(Employee $employee, EmployeeJobProfile $employeeJobProfile)
    {
        return view('employee.job-profile.edit', compact('employee', 'employeeJobProfile'));
    }

    public function update(Request $request, Employee $employee, $id)
    {
        $employee = Employee::findOrFail($id);
        $user     = User::where('employee_id', $id)->firstOrFail();
        $all      = $request->all();

        $validator = $this->validator($all);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {


            return redirect()->route('employee.index')->with('success', 'تم تعديل بيانات الموظف.');
        }
    }

}
