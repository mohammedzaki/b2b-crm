<?php

namespace App\Http\Controllers\HR\PayrollManagement;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\AbsentType;
use App\Models\Attendance;
use App\Models\ClientProcess;
use App\Models\Employee;
use Illuminate\Http\Request;
use Auth;
use Validator;

/**
 * @Controller(prefix="attendance")
 * @Resource("attendance")
 * @Middleware({"web", "auth"})
 */
class AttendanceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $startDate = DateTime::today()->format('Y-m-d 00:00:00');
        $endDate   = DateTime::today()->format('Y-m-d 23:59:59');

        return $this->getAttendanceItems('all', $startDate, $endDate, 1, TRUE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Get("search/{employee_id}", as="attendance.search")
     */
    public function search($employee_id, Request $request)
    {
        $user = Auth::user();
        if (!$user->ability('admin', 'attendance-edit')) {
            return response()->view('errors.403', [], 403);
        }
        $date = DateTime::parse($request['date']);
        return $this->getAttendanceItems($employee_id, $date, null, 1);
    }

    private function getAttendanceItems($id, $startDate, $endDate, $canEdit, $isToday = FALSE)
    {
        $employees = Employee::all()->mapWithKeys(function ($employee) {
            return [$employee->id => $employee->name];
        });
        $dt        = DateTime::parse($startDate);
        $hasData   = FALSE;
        if ($id == "all") {
            $attendances = []; //Attendance::all();
            $attendances = Attendance::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
            $employee_id = 0;
            $startDate   = null;
            $id          = 0;
        } else {
            $employee = Employee::findOrFail($id);
            if ($isToday) {
                $attendances = Attendance::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
            } else {
                $attendances = Attendance::where([
                            ['employee_id', '=', $id]
                        ])
                        ->whereYear('date', $dt->year)
                        ->whereMonth('date', $dt->month)->get();
            }
            $hasData = TRUE;
        }
        $date        = $startDate;
        $employee_id = $id;

        foreach ($attendances as $attendance) {
            $attendance->workingHours = $attendance->workingHoursToString();
            $attendance->employeeName = $attendance->employee->name;
            $attendance->date         = DateTime::parse($attendance->date)->format('l, d-m-Y');
            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
        }

        return view('attendance.index', compact(['employees', 'attendances', 'date', 'employee_id', 'hasData']));
    }

    protected function validatorCheckin(array $data, $id = null)
    {
        $validator = Validator::make($data, [
                    'process_id'           => 'required_without:is_managment_process',
                    'is_managment_process' => 'required_if:process_id,-1',
                    'employee_id'          => 'exists:employees,id|required',
                        //'notes' => 'string'
        ]);

        $validator->setAttributeNames([
            'process_id'           => 'اسم العملية',
            'employee_id'          => 'اسم الموظف',
            'is_managment_process' => 'عمليات ادارية',
                //'notes' => 'ملاحظات'
        ]);

        return $validator;
    }

    protected function validatorCheckout(array $data, $id = null)
    {
        $validator = Validator::make($data, [
                    'employee_id' => 'exists:employees,id|required',
                        //'notes' => 'string'
        ]);

        $validator->setAttributeNames([
            'employee_id' => 'اسم الموظف',
                //'notes' => 'ملاحظات'
        ]);

        return $validator;
    }

    public function getBasicData($checkin = true, $attendance = null)
    {
        $employees = Employee::all()->mapWithKeys(function ($employee) {
            return [$employee->id => $employee->name];
        });
        $processes = ClientProcess::allOpened()->get()->mapWithKeys(function ($process) {
            return [$process->id => $process->name];
        });
        $absentTypes = AbsentType::all()->mapWithKeys(function ($type) {
            return [$type->id => $type->name];
        });
        $absentTypesInfo = AbsentType::all()->mapWithKeys(function ($type) {
            return [
                $type->id => [
                    'salaryDeduction' => $type->salary_deduction,
                    'editable'        => $type->editable_deduction
                ]
            ];
        });
        $employeesSalaries = Employee::all()->mapWithKeys(function ($employee) use ($attendance) {
            return [
                $employee['id'] => [
                    'dailySalary' => $employee->currentJobProfile->daily_salary
                ]
            ];
        });
        $employeesCheckinDates = [];
        $checkinbtn            = FALSE;
        return compact('attendance', 'employees', 'employeesSalaries', 'processes', 'absentTypes', 'absentTypesInfo', 'checkin', 'checkinbtn', 'employeesCheckinDates');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('attendance.create', $this->getBasicData());
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("checkin", as="attendance.checkin")
     */
    public function checkin()
    {
        return $this->createAttendance(TRUE);
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("checkout", as="attendance.checkout")
     */
    public function checkout()
    {
        return $this->createAttendance(FALSE);
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("manualadding", as="attendance.manualadding")
     */
    public function manualadding()
    {
        return $this->createAttendance();
    }

    function createAttendance($checkType = -1)
    {
        return view('attendance.create', $this->getBasicData($checkType));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $all = $request->all();
        $date =  DateTime::parse($all['date']);
        if (!empty($request->check_in)) {
            $validator = $this->validatorCheckin($all);
        } else {
            $validator = $this->validatorCheckout($all);
        }
        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $attendance            = Attendance::where("employee_id", $all['employee_id'])
                    ->orderBy('id', 'desc')
                    ->first();
            $attendanceToday       = Attendance::where([
                        ["date", "=", $date],
                        ["employee_id", "=", $all['employee_id']]
                    ])
                    ->orderBy('id', 'desc')
                    ->first();
            $attendanceBeforeToday = Attendance::where([
                        ["date", "=", DateTime::parse($all['date'])->addDay(-1)],
                        ["employee_id", "=", $all['employee_id']]
                    ])
                    ->orderBy('id', 'desc')
                    ->first();
            if ($request->checkin == -1) {
                $attendance          = Attendance::firstOrCreate([
                            ["date", "=", $date],
                            ["employee_id", "=", $all['employee_id']]
                ]);
                $all['absent_check'] = TRUE;
                goto skip;
            } else if ($request->checkin == 0) {

            } else if (!empty($attendanceToday)) {
                if (isset($attendanceToday->absent_check)) {
                    return redirect()->back()->withInput($all)->with('error', 'لقد تم تسجيل غياب لهذا العامل يرجى التعديل من شاشة التعديل');
                } else if (isset($attendanceToday->check_in) && isset($attendanceToday->check_out) && !isset($request->is_second_shift)) {
                    return redirect()->back()->withInput($all)->with('error', 'لقد تم تسجيل حضور و انصراف هذا العامل يرجى التعديل من شاشة التعديل');
                } else if (isset($request->check_in)) {
                    $checkinDate  = DateTime::parse($request->check_in);
                    $checkoutDate = DateTime::parse($attendanceToday->check_out);
                    if (empty($attendanceToday->check_out)) {
                        return redirect()->back()->withInput($all)->with('error', 'يجب تسجيل انصراف اولا');
                    } else if ($checkoutDate->gt($checkinDate)) {
                        return redirect()->back()->withInput($all)->with('error', 'تاريخ الدخول اكبر من اخر تاريخ انصراف');
                    }
                } else if (isset($request->check_out)) {
                    $checkinDate  = DateTime::parse($attendanceToday->check_in);
                    $checkoutDate = DateTime::parse($request->check_out);
                    if (isset($request->is_second_shift)) {
                        if ($attendanceToday->shift == 1) {
                            return redirect()->back()->withInput($all)->with('error', 'يجب تسجيل حضور الوردية الاولى اولا');
                        }
                    } else if (empty($attendanceToday->check_in)) {
                        return redirect()->back()->withInput($all)->with('error', 'يجب تسجيل حضور اولا');
                    } else if ($checkinDate->gt($checkoutDate)) {
                        return redirect()->back()->withInput($all)->with('error', 'تاريخ الانصراف اقل من اخر تاريخ دخول');
                    }
                }
                if (isset($attendanceToday->check_in) && isset($attendanceToday->check_out) && isset($request->is_second_shift)) {
                    $attendance   = Attendance::firstOrCreate([
                                ["date", "=", $date],
                                ["employee_id", "=", $all['employee_id']],
                                ["shift", "=", 2]
                    ]);
                    $all['shift'] = 2;
                    goto skip;
                }
            } else if (empty($attendanceBeforeToday)) {
                return redirect()->back()->withInput($all)->with('error', 'يجب تسجيل اليوم السابق اولا');
            } else { // There are no checkin at this day
                if (isset($request->is_second_shift)) {
                    $errors['is_second_shift'] = "يجب تسجيل الوردية الاولى اولا";
                    return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($errors);
                } else if (isset($request->check_in)) {

                } else if (isset($request->check_out)) {
                    $checkinDate  = DateTime::parse($attendance->check_in);
                    $checkoutDate = DateTime::parse($request->check_out);
                    if (empty($attendance->check_in)) {
                        return redirect()->back()->withInput($all)->with('error', 'يجب تسجيل حضور اولا');
                    } else if ($checkinDate->gt($checkoutDate)) {
                        return redirect()->back()->withInput($all)->with('error', 'تاريخ الانصراف اقل من اخر تاريخ دخول');
                    }
                }
            }
            $attendance   = Attendance::firstOrCreate([
                        ["date", "=", $date],
                        ["employee_id", "=", $all['employee_id']],
                        ["shift", "=", 1]
            ]);
            $all['shift'] = 1;
            skip:
            if (isset($request->is_managment_process)) {
                $all['process_id'] = null;
            }
            $attendance->update($all);
            if ($request->checkin) {
                return redirect()->route('attendance.checkin')->with(['success' => 'تم حفظ البيانات.']);
            } else {
                return redirect()->route('attendance.checkout')->with(['success' => 'تم حفظ البيانات.']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return redirect()->route('attendance.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attendance               = Attendance::findOrFail($id);
        $check_out                = DateTime::parse($attendance->check_out);
        $check_in                 = DateTime::parse($attendance->check_in);
        //$attendance->check_out = $attendance->check_out
        $attendance->workingHours = $check_out->diffInHours($check_in);
        $attendance->employeeName = $attendance->employee->name;
        if ($attendance->process) {
            $attendance->processName = $attendance->process->name;
        } else {
            $attendance->processName          = "عمليات ادارية";
            $attendance->is_managment_process = TRUE;
        }
        if ($attendance->absentType) {
            $attendance->absentTypeName = $attendance->absentType->name;
        }
        return view('attendance.edit', $this->getBasicData(true, $attendance));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $all        = $request->all();

        $validator  = $this->validatorCheckin($all, $attendance->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            if (isset($request->is_managment_process)) {
                $all['process_id'] = null;
            }
            if (!isset($request->absent_check)) {
                $all['absent_check']     = null;
                $all['absent_type_id']   = 0;
                $all['absent_deduction'] = 0;
            }
            $attendance->update($all);
            return redirect()->back()->withInput($all)->with(['success' => 'تم حفظ البيانات.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'تم الحذف .');
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("getEmployeesCheckinDate", as="attendance.getEmployeesCheckinDate")
     */
    public function getEmployeesCheckinDate(Request $request)
    {
        $shift      = ($request->is_second_shift == "true" ? 2 : 1);
        $attendance = Attendance::where([
                    ["date", "=", $request->date],
                    ["employee_id", "=", $request->employee_id],
                    ["shift", "=", $shift]
                ])->first();
        if (empty($attendance))
            return "";
        else
            return $attendance->check_in;
    }

}
