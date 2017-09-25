<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Employee;
use Crypt;

/**
 * @Controller(prefix="/employee")
 * @Resource("employee")
 * @Middleware({"web", "auth", "ability:admin,employees-permissions"})
 */
class EmployeeController extends Controller {

    protected function validator(array $data) {
        $validator = Validator::make($data, [
                    'name'                => 'required|min:6|max:255',
                    'ssn'                 => 'required|digits:14',
                    'gender'              => 'required|in:m,f',
                    'martial_status'      => 'in:single,married,widowed,divorced',
                    'birth_date'          => 'required|date_format:Y-m-d',
                    'department'          => 'string',
                    'hiring_date'         => 'required|date_format:Y-m-d',
                    'daily_salary'        => 'required|numeric',
                    'working_hours'       => 'required|numeric',
                    'job_title'           => 'required|max:100',
                    'telephone'           => 'digits:8',
                    'mobile'              => 'required|digits:11',
                    'can_not_use_program' => 'boolean',
                    'borrow_system'       => 'boolean',
                    'username'            => 'required_without:can_not_use_program|unique:users,username',
                    'password'            => 'required_without:can_not_use_program'
        ]);

        $validator->setAttributeNames([
            'name'                => 'اسم الموظف',
            'ssn'                 => 'الرقم القومي',
            'gender'              => 'الجنس',
            'martial_status'      => 'الحالة اﻻجتماعية',
            'birth_date'          => 'تاريخ الميﻻد',
            'department'          => 'القسم',
            'hiring_date'         => 'تاريخ التعيين',
            'daily_salary'        => 'الراتب اليومي',
            'working_hours'       => 'ساعات العمل',
            'job_title'           => 'الوظيفة',
            'telephone'           => 'التليفون',
            'mobile'              => 'المحمول',
            'can_not_use_program' => 'عدم استخدام البرنامج',
            'borrow_system'       => 'نظام السلف',
            'username'            => 'اسم المستخدم',
            'password'            => 'كلمة المرور'
        ]);

        return $validator;
    }

    public function index() {
        $employees = Employee::all();
        return view('employee.index', compact('employees'));
    }

    public function create() {
        $permissions = Permission::all();
        return view('employee.create', compact('permissions'));
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {
            /* Create employee object */
            $employee                      = new Employee();
            $employee->emp_id              = $employee->max('emp_id') + 1;
            $employee->name                = $request->name;
            $employee->ssn                 = $request->ssn;
            $employee->gender              = $request->gender;
            $employee->martial_status      = $request->martial_status;
            $employee->birth_date          = $request->birth_date;
            $employee->department          = $request->department;
            $employee->hiring_date         = $request->hiring_date;
            $employee->daily_salary        = $request->daily_salary;
            $employee->working_hours       = $request->working_hours;
            $employee->job_title           = $request->job_title;
            $employee->telephone           = $request->telephone;
            $employee->mobile              = $request->mobile;
            $employee->facility_id         = 1;
            $employee->can_not_use_program = ($request->can_not_use_program) ? $request->can_not_use_program : false;
            $employee->borrow_system       = ($request->borrow_system) ? $request->borrow_system : false;
            $employee->save();

            $user = new User();
            if ($request->username) {
                $user->username = $request->username;
            } else {
                $user->username = "user_" . str_random(5);
            }
            if ($request->password) {
                $user->password = bcrypt($request->password);
            } else {
                $user->password = bcrypt(str_random(40));
            }
            $user->employee_id = $employee->id;
            $user->save();

            /* create new user role */
            $role       = new Role();
            $role->name = 'role_' . $employee->id;
            $role->save();
            /* attatch role to user */
            $user->attachRole($role);
            /* attatch permissions to role */
            if ($request->permissions) {
                foreach ($request->permissions as $permission) {
                    $role->attachPermission(Permission::find($permission));
                }
            }
            return redirect()->route('employee.index')
                            ->with('success', 'تم اضافة موظف جديد.');
        }
    }

    public function destroy($id) {
        $employee = Employee::where('id', $id)->firstOrFail();
        foreach ($employee->users as $user) {
            $user->delete();
        }
        $employee->deleted_at_id = $employee->emp_id;
        $employee->emp_id        = NULL;
        $employee->save();
        $employee->delete();
        return redirect()->back()->with('success', 'تم حذف موظف.');
    }

    public function edit($id) {
        $employee           = Employee::findOrFail($id);
        $employee->username = $employee->users()->first()->username;
        $employeePermissions = $employee->users[0]->roles->first()->perms;
        $selectedPermissions = [];
        foreach ($employeePermissions as $p) {
            array_push($selectedPermissions, $p->id);
        }
        $permissions = Permission::all();
        return view('employee.edit', compact('employee', 'permissions', 'selectedPermissions', 'username'));
    }

    public function update(Request $request, $id) {
        $employee = Employee::findOrFail($id);
        $user     = User::where('employee_id', $id)->firstOrFail();
        /* copy request variables */
        $all      = $request->all();

        /**
         * If username is giving check it's value whether it has been changed or not
         * If it was not changed then remove it from validation, because of unique
         * option causes error.
         */
        if ($request->username) {
            $user->username = $request->username;
            if (!$user->isDirty('username')) {
                $all['username'] = "isDirtyAndUpdated";
            }
        }
        if (empty($request->password)) {
            $all['password'] = "noChange";
        }
        $validator = $this->validator($all);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        } else {

            if ($all['password'] != "noChange") {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            $employee->name                = $request->name;
            $employee->ssn                 = $request->ssn;
            $employee->gender              = $request->gender;
            $employee->martial_status      = $request->martial_status;
            $employee->birth_date          = $request->birth_date;
            $employee->department          = $request->department;
            $employee->hiring_date         = $request->hiring_date;
            $employee->daily_salary        = $request->daily_salary;
            $employee->working_hours       = $request->working_hours;
            $employee->job_title           = $request->job_title;
            $employee->telephone           = $request->telephone;
            $employee->mobile              = $request->mobile;
            $employee->can_not_use_program = ($request->can_not_use_program) ? $request->can_not_use_program : false;
            $employee->borrow_system       = ($request->borrow_system) ? $request->borrow_system : false;
            $employee->save();

            $role = $user->roles->first();
            $role->detachPermissions($role->perms);

            /* attatch permissions to role */
            if ($request->permissions) {
                foreach ($request->permissions as $permission) {
                    $role->attachPermission(Permission::find($permission));
                }
            }
            return redirect()->route('employee.index')->with('success', 'تم تعديل بيانات الموظف.');
        }
    }

    /**
     * @return \Illuminate\Http\Response
     * @Get("/trash", as="employee.trash")
     */
    public function trash() {
        $employees = Employee::onlyTrashed()->get();
        return view('employee.trash', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return Response
     * @Get("/restore/{id}", as="employee.restore")
     */
    public function restore($id) {
        $employee                = Employee::withTrashed()->find($id)->restore();
        $employee->emp_id        = $employee->deleted_at_id;
        $employee->deleted_at_id = NULL;
        $employee->save();
        return redirect()->route('employee.index')->with('success', 'تم استرجاع عميل جديد.');
    }

}
