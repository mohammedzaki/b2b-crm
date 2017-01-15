<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests;
use App\Client;
use App\Supplier;
use App\ClientProcess;
use App\Employee;
use App\Expenses;
use App\SupplierProcess;
use App\Attendance;
use App\Facility;
use App\AbsentType;
use App\User;
use Validator;

class AttendanceController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //$this->middleware('ability:admin,deposit-withdraw');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {
            //$attendance->check_out - $attendance->check_in
            $check_out = Carbon::parse($attendance->check_out);
            $check_in = Carbon::parse($attendance->check_in);
            //$attendance->check_out = $attendance->check_out
            $attendance->workingHours = $check_out->diffInHours($check_in);
            $attendance->employeeName = $attendance->employee->name;

            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
        }
        return view('attendance.index', compact(['attendances']));
    }

    protected function validator(array $data, $id = null) {
        $validator = Validator::make($data, [
                    //'process_id' => 'exists:client_processes,id|required_without:is_managment_process',
                    //'is_managment_process' => 'required_without:process_id',
                    'employee_id' => 'exists:employees,id|required',
                    'notes' => 'string'
        ]);

        $validator->setAttributeNames([
            //'process_id' => 'اسم العملية',
            'employee_id' => 'اسم الموظف',
            //'is_managment_process' => 'عمليات ادارية',
            'notes' => 'ملاحظات'
        ]);

        return $validator;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $employees = Employee::all();
        $processes = ClientProcess::allOpened()->get();
        $absentTypes = AbsentType::all();
        $employees_tmp = [];
        $employeesSalaries = [];
        $processes_tmp = [];
        $absentTypes_tmp = [];
        $absentTypesInfo = [];
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
            $employeesSalaries[$employee->id]['hourlySalary'] = $employee->daily_salary / $employee->working_hours;
        }
        foreach ($processes as $process) {
            $processes_tmp[$process->id] = $process->name;
        }
        foreach ($absentTypes as $type) {
            $absentTypes_tmp[$type->id] = $type->name;
            $absentTypesInfo[$type->id]['salaryDeduction'] = $type->salary_deduction;
            $absentTypesInfo[$type->id]['editable'] = $type->editable_deduction;
        }
        $processes = $processes_tmp;
        $employees = $employees_tmp;
        $absentTypes = $absentTypes_tmp;
        $checkin = TRUE;
        return view('attendance.create', compact(['employees', 'employeesSalaries', 'processes', 'absentTypes', 'absentTypesInfo', 'checkin']));
    }

    public function checkin() {
        $employees = Employee::all();
        $processes = ClientProcess::allOpened()->get();
        $absentTypes = AbsentType::all();
        $employees_tmp = [];
        $employeesSalaries = [];
        $processes_tmp = [];
        $absentTypes_tmp = [];
        $absentTypesInfo = [];
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
            $employeesSalaries[$employee->id]['hourlySalary'] = $employee->daily_salary / $employee->working_hours;
        }
        foreach ($processes as $process) {
            $processes_tmp[$process->id] = $process->name;
        }
        foreach ($absentTypes as $type) {
            $absentTypes_tmp[$type->id] = $type->name;
            $absentTypesInfo[$type->id]['salaryDeduction'] = $type->salary_deduction;
            $absentTypesInfo[$type->id]['editable'] = $type->editable_deduction;
        }
        $processes = $processes_tmp;
        $employees = $employees_tmp;
        $absentTypes = $absentTypes_tmp;
        $checkin = TRUE;
        $checkinbtn = FALSE;
        return view('attendance.create', compact(['employees', 'employeesSalaries', 'processes', 'absentTypes', 'absentTypesInfo', 'checkin', 'checkinbtn']));
    }

    public function checkout() {
        $employees = Employee::all();
        $processes = ClientProcess::allOpened()->get();
        $absentTypes = AbsentType::all();
        $employees_tmp = [];
        $employeesSalaries = [];
        $processes_tmp = [];
        $absentTypes_tmp = [];
        $absentTypesInfo = [];
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
            $employeesSalaries[$employee->id]['hourlySalary'] = $employee->daily_salary / $employee->working_hours;
        }
        foreach ($processes as $process) {
            $processes_tmp[$process->id] = $process->name;
        }
        foreach ($absentTypes as $type) {
            $absentTypes_tmp[$type->id] = $type->name;
            $absentTypesInfo[$type->id]['salaryDeduction'] = $type->salary_deduction;
            $absentTypesInfo[$type->id]['editable'] = $type->editable_deduction;
        }
        $processes = $processes_tmp;
        $employees = $employees_tmp;
        $absentTypes = $absentTypes_tmp;
        $checkin = FALSE;
        $checkinbtn = FALSE;
        return view('attendance.create', compact(['employees', 'employeesSalaries', 'processes', 'absentTypes', 'absentTypesInfo', 'checkin', 'checkinbtn']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = $this->validator($request->all());
        $all = $request->all();
        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            $attendance = Attendance::firstOrCreate([
                            ["date", "=", $all['date']],
                            ["employee_id", "=", $all['employee_id']]
            ]);
            if (isset($request->is_managment_process)) {
                $all['process_id'] = null;
            }
            $attendance->update($all);
            return redirect()->route('attendance.edit', $attendance->id)->with(['success' => 'تم حفظ البيانات.', 'checkin' => $request->checkin]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $employees = Employee::all();
        $dt = Carbon::parse($request->date);
        $hourlyRate = 0;
        if ($id == "all") {
            $attendances = []; //Attendance::all();
            $employee_id = 0;
            $date = null;
        } else {
            $employee = Employee::findOrFail($id);
            $hourlyRate = $employee->daily_salary / $employee->working_hours;
            $attendances = Attendance::where([
                            ['employee_id', '=', $id]
                    ])->whereMonth('date', '=', $dt->month)->get();
        }
        $date = $request->date;
        $employee_id = $id;
        $employees_tmp = [];
        $totalWorkingHours = 0;
        $totalSalaryDeduction = 0;
        $totalAbsentDeduction = 0;
        $totalBonuses = 0;
        $totalHoursSalary = 0;
        $totalSalary = 0;
        $totalNetSalary = 0;
        $totalGuardianshipValue = 0;
        $totalGuardianshipReturnValue = 0;
        $totalBorrowValue = 0;
        $totalSmallBorrowValue = 0;
        $totalLongBorrowValue = 0;
        foreach ($attendances as $attendance) {
            //$attendance->check_out - $attendance->check_in
            $check_out = Carbon::parse($attendance->check_out);
            $check_in = Carbon::parse($attendance->check_in);
            //$attendance->check_out = $attendance->check_out
            $attendance->workingHours = $check_out->diffInHours($check_in);
            $attendance->employeeName = $attendance->employee->name;

            $attendance->GuardianshipValue = $attendance->employeeGuardianship();
            $attendance->GuardianshipReturnValue = $attendance->employeeGuardianshipReturn();
            $attendance->borrowValue = $attendance->employeeSmallBorrow();

            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
            $totalWorkingHours += $attendance->workingHours;
            $totalSalaryDeduction += $attendance->salary_deduction;
            $totalAbsentDeduction += $attendance->absent_deduction;
            $totalBonuses += $attendance->mokaf;
            $totalGuardianshipValue += $attendance->GuardianshipValue;
            $totalGuardianshipReturnValue += $attendance->GuardianshipReturnValue;
            $totalBorrowValue += $attendance->borrowValue;
        }
        try {
            $attendances[0]->borrowValue = $attendances[0]->employeeLongBorrow();
            $totalLongBorrowValue = $attendances[0]->employeeLongBorrow();
        } catch (\Exception $exc) {
            
        }
        $totalSmallBorrowValue = $totalBorrowValue;
        $totalBorrowValue += $totalLongBorrowValue;
        $totalHoursSalary = $totalWorkingHours * $hourlyRate;
        $totalSalary = ($totalHoursSalary + $totalBonuses);
        $totalNetSalary = $totalSalary - ($totalSalaryDeduction + $totalAbsentDeduction + ($totalGuardianshipValue - $totalGuardianshipReturnValue) + $totalSmallBorrowValue + $totalLongBorrowValue);

        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $employees = $employees_tmp;
        return view('attendance.show', compact(['employees', 'attendances', "hourlyRate", "totalWorkingHours", "totalSalaryDeduction", "totalAbsentDeduction", "totalBonuses", "totalSalary", 'totalHoursSalary', 'totalNetSalary', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalBorrowValue', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'employee_id', 'date']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $attendance = Attendance::findOrFail($id);
        $check_out = Carbon::parse($attendance->check_out);
        $check_in = Carbon::parse($attendance->check_in);
        //$attendance->check_out = $attendance->check_out
        $attendance->workingHours = $check_out->diffInHours($check_in);
        $attendance->employeeName = $attendance->employee->name;
        if ($attendance->process) {
            $attendance->processName = $attendance->process->name;
        } else {
            $attendance->processName = "عمليات ادارية";
            $attendance->is_managment_process = TRUE;
        }
        if ($attendance->absentType) {
            $attendance->absentTypeName = $attendance->absentType->name;
        }
        $employees = Employee::all();
        $processes = ClientProcess::allOpened()->get();
        $absentTypes = AbsentType::all();
        $employees_tmp = [];
        $employeesSalaries = [];
        $processes_tmp = [];
        $absentTypes_tmp = [];
        $absentTypesInfo = [];
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
            $employeesSalaries[$employee->id]['hourlySalary'] = $employee->daily_salary / $employee->working_hours;
        }
        foreach ($processes as $process) {
            $processes_tmp[$process->id] = $process->name;
        }
        foreach ($absentTypes as $type) {
            $absentTypes_tmp[$type->id] = $type->name;
            $absentTypesInfo[$type->id]['salaryDeduction'] = $type->salary_deduction;
            $absentTypesInfo[$type->id]['editable'] = $type->editable_deduction;
        }
        $processes = $processes_tmp;
        $employees = $employees_tmp;
        $absentTypes = $absentTypes_tmp;
        $checkin = TRUE;
        return view('attendance.edit', compact(['attendance', 'employees', 'employeesSalaries', 'processes', 'absentTypes', 'absentTypesInfo', 'checkin']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $attendance = Attendance::findOrFail($id);
        $all = $request->all();
        $validator = $this->validator($all, $attendance->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput($all)->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
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
    public function destroy($id) {
        //
    }

    public function employee() {
        $employees = Employee::all();
        $attendances = Attendance::all();
        $employees_tmp = [];

        foreach ($attendances as $attendance) {
            //$attendance->check_out - $attendance->check_in
            $check_out = Carbon::parse($attendance->check_out);
            $check_in = Carbon::parse($attendance->check_in);
            //$attendance->check_out = $attendance->check_out
            $attendance->workingHours = $check_out->diffInHours($check_in);
            $attendance->employeeName = $attendance->employee->name;
            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
        }
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $employees = $employees_tmp;
        return view('attendance.employee', compact(['employees', 'attendances']));
    }

    public function guardianship(Request $request, $employee_id) {
        $employees = Employee::all();
        $dt = Carbon::parse($request->date);
        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        if ($employee_id == "all") {
            $employeeGuardianships = []; //Attendance::all();
            $employee_id = 0;
            $date = null;
        } else {
            $employee = Employee::findOrFail($employee_id);
            $employeeGuardianships = $employee->employeeGuardianships($dt->month);
        }
        $date = $request->date;
        $employee_id = $employee_id;
        $totalGuardianshipValue = 0;
        $totalGuardianshipReturnValue = 0;

        foreach ($employeeGuardianships as $guardianship) {
            $totalGuardianshipValue += $guardianship->withdrawValue;
            $totalGuardianshipReturnValue += $guardianship->depositValue;
        }
        $employees = $employees_tmp;
        return view("attendance.guardianship", compact(['employees', 'employeeGuardianships', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'employee_id', 'date']));
    }
    
    public function guardianshipaway(Request $request, $id) {
        
        return "تم الترحيل";
    }
    
    public function printSalaryReport(Request $request, $id) {
        
        $employees = Employee::all();
        $dt = Carbon::parse($request->date);
        $hourlyRate = 0;
        if ($id == "all") {
            $attendances = []; //Attendance::all();
            $employee_id = 0;
            $date = null;
        } else {
            $employee = Employee::findOrFail($id);
            $hourlyRate = $employee->daily_salary / $employee->working_hours;
            $attendances = Attendance::where([
                            ['employee_id', '=', $id]
                    ])->whereMonth('date', '=', $dt->month)->get();
        }
        $date = $request->date;
        $employee_id = $id;
        $employees_tmp = [];
        $totalWorkingHours = 0;
        $totalSalaryDeduction = 0;
        $totalAbsentDeduction = 0;
        $totalBonuses = 0;
        $totalHoursSalary = 0;
        $totalSalary = 0;
        $totalNetSalary = 0;
        $totalGuardianshipValue = 0;
        $totalGuardianshipReturnValue = 0;
        $totalBorrowValue = 0;
        $totalSmallBorrowValue = 0;
        $totalLongBorrowValue = 0;
        foreach ($attendances as $attendance) {
            //$attendance->check_out - $attendance->check_in
            $check_out = Carbon::parse($attendance->check_out);
            $check_in = Carbon::parse($attendance->check_in);
            //$attendance->check_out = $attendance->check_out
            $attendance->workingHours = $check_out->diffInHours($check_in);
            $attendance->employeeName = $attendance->employee->name;

            $attendance->GuardianshipValue = $attendance->employeeGuardianship();
            $attendance->GuardianshipReturnValue = $attendance->employeeGuardianshipReturn();
            $attendance->borrowValue = $attendance->employeeSmallBorrow();

            if ($attendance->process) {
                $attendance->processName = $attendance->process->name;
            } else {
                $attendance->processName = "عمليات ادارية";
            }
            if ($attendance->absentType) {
                $attendance->absentTypeName = $attendance->absentType->name;
            }
            $totalWorkingHours += $attendance->workingHours;
            $totalSalaryDeduction += $attendance->salary_deduction;
            $totalAbsentDeduction += $attendance->absent_deduction;
            $totalBonuses += $attendance->mokaf;
            $totalGuardianshipValue += $attendance->GuardianshipValue;
            $totalGuardianshipReturnValue += $attendance->GuardianshipReturnValue;
            $totalBorrowValue += $attendance->borrowValue;
        }
        try {
            $attendances[0]->borrowValue = $attendances[0]->employeeLongBorrow();
            $totalLongBorrowValue = $attendances[0]->employeeLongBorrow();
        } catch (\Exception $exc) {
            
        }
        $totalSmallBorrowValue = $totalBorrowValue;
        $totalBorrowValue += $totalLongBorrowValue;
        $totalHoursSalary = $totalWorkingHours * $hourlyRate;
        $totalSalary = ($totalHoursSalary + $totalBonuses);
        $totalNetSalary = $totalSalary - ($totalSalaryDeduction + $totalAbsentDeduction + ($totalGuardianshipValue - $totalGuardianshipReturnValue) + $totalSmallBorrowValue + $totalLongBorrowValue);

        foreach ($employees as $employee) {
            $employees_tmp[$employee->id] = $employee->name;
        }
        $employees = $employees_tmp;
        $employeeName = $employee->name;
        $pdfReport = new \App\Reports\Employee\Salary(TRUE);
        
        $pdfReport->htmlContent = view('reports.employee.salary', compact(['employeeName','employees', 'attendances', "hourlyRate", "totalWorkingHours", "totalSalaryDeduction", "totalAbsentDeduction", "totalBonuses", "totalSalary", 'totalHoursSalary', 'totalNetSalary', 'totalGuardianshipValue', 'totalGuardianshipReturnValue', 'totalBorrowValue', 'totalLongBorrowValue', 'totalSmallBorrowValue', 'employee_id', 'date']))->render();
        
        
        //$pdfReport->employeeName = "Mai Gado";
        return $pdfReport->RenderReport();
    }

}
