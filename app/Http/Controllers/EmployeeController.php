<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

use App\User;
use App\Role;
use App\Permission;
use App\Employee;

class EmployeeController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	    $this->middleware('ability:admin,employees-permissions');
	}

	protected function validator(array $data)
	{
	    $validator = Validator::make($data, [
	        'name' => 'required|min:6|max:255',
	        'ssn' => 'required|digits:14',
	        'gender' => 'required|in:m,f',
	        'martial_status' => 'in:single,married,widowed,divorced',
	        'birth_date' => 'required|date_format:Y-m-d',
	        'department' => 'string',
	        'hiring_date' => 'required|date_format:Y-m-d',
	        'daily_salary' => 'required|numeric',
	        'working_hours' => 'required|numeric',
	        'job_title' => 'required|max:100',
	        'telephone' => 'digits:8',
	        'mobile' => 'required|digits:11',
	        'can_not_use_program' => 'boolean',
	        'is_active' => 'boolean',
	        'borrow_system' => 'boolean',
	        'username' => 'unique:users,username',
	        'password' => 'min:4'
	    ]);

	    $validator->setAttributeNames([
	        'name' => 'اسم الموظف',
	        'ssn' => 'الرقم القومي',
	        'gender' => 'الجنس',
	        'martial_status' => 'الحالة اﻻجتماعية',
	        'birth_date' => 'تاريخ الميﻻد',
	        'department' => 'القسم',
	        'hiring_date' => 'تاريخ التعيين',
	        'daily_salary' => 'الراتب اليومي',
	        'working_hours' => 'ساعات العمل',
	        'job_title' => 'الوظيفة',
	        'telephone' => 'التليفون',
	        'mobile' => 'المحمول',
	        'can_not_use_program' => 'عدم استخدام البرنامج',
	        'is_active' => 'نشط',
	        'borrow_system' => 'نظام السلف',
	        'username' => 'اسم المستخدم',
	        'password' => 'كلمة المرور'
	    ]);

	    return $validator;
	}

    public function index()
    {   
        $employees = Employee::all();
    	return view('employee.index', compact('employees'));
    }

    public function create()
    {	
    	$permissions = Permission::all();
    	return view('employee.create', compact('permissions'));
    }

    public function store(Request $request)
    {
    	$validator = $this->validator( $request->all() );

    	if( $validator->fails() ){
    	    return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
    	}else{
    		$user = new User();
    		if($request->username){
    			$user->username = $request->username;
    		}else{
    			$user->username = "user_".str_random(5);
    		}
    		
    		if($request->password){
    			$user->password = bcrypt($request->password);
    		}else{
    			$user->password = bcrypt(str_random(40));	
    		}
    		$user->save();
    		/* Create employee object */
    		$employee = new Employee();
    		$employee->user_id = $user->id;
    		$employee->name = $request->name;
    		$employee->ssn = $request->ssn;
    		$employee->gender = $request->gender;
    		$employee->martial_status = $request->martial_status;
    		$employee->birth_date = $request->birth_date;
    		$employee->department = $request->department;
    		$employee->hiring_date = $request->hiring_date;
    		$employee->daily_salary = $request->daily_salary;
    		$employee->working_hours = $request->working_hours;
    		$employee->job_title = $request->job_title;
    		$employee->telephone = $request->telephone;
    		$employee->mobile = $request->mobile;
    		$employee->facility_id = 1;
    		$employee->can_not_use_program = ($request->can_not_use_program) ? $request->can_not_use_program : false;
    		$employee->is_active = ($request->is_active) ? $request->is_active : false;
    		$employee->borrow_system = ($request->borrow_system) ? $request->borrow_system : false;
    		$employee->save();

    		/* create new user role */
    		$role = new Role();
    		$role->name = 'role_'.$employee->user_id;
    		$role->save();
    		/* attatch role to user */
    		$user->attachRole($role);
    		/* attatch permissions to role */
            if($request->permissions){
               foreach ($request->permissions as $permission) {
                $role->attachPermission(Permission::find($permission));
               } 
            }
    		return redirect()->route('employee.index')
    			->with('success', 'تم اضافة موظف جديد.');
    	}
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $roles = $user->roles;
        foreach ($roles as $role) {
            $role->detachPermissions($role->permissions);
            $user->detachRole($role);
            $role->delete();
        }
        $employee = Employee::where('user_id', $id)->firstOrFail();
        // dd($employee);
        $employee->delete();
        $user->delete();

        return redirect()->back()->with('success', 'تم حذف موظف.');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->username = $employee->user->username;
        $employeePermissions = $employee->user->roles->first()->perms;
        $selectedPermissions = [];
        foreach ($employeePermissions as $p) {
            array_push($selectedPermissions, $p->id);
        }
        $permissions = Permission::all();
        return view('employee.edit', compact('employee', 
            'permissions', 'selectedPermissions'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $user = User::findOrFail($id);
        /* copy request variables */
        $all = $request->all();

        /**
        * If username is giving check it's value whether it has been changed or not
        * If it was not changed then remove it from validation, because of unique
        * option causes error.
        */
        if($request->username){
            $user->username = $request->username;
            if(!$user->isDirty('username')){
                $all = array_except($all, 'username');
            }
        }

        $validator = $this->validator( $all );

        if( $validator->fails() ){
            return redirect()->back()->withInput()->with('error', 'حدث حطأ في حفظ البيانات.')->withErrors($validator);
        }else{
        
            if($request->password){
                $user->password = bcrypt($request->password);
            }

            $user->save();
            
            $employee->name = $request->name;
            $employee->ssn = $request->ssn;
            $employee->gender = $request->gender;
            $employee->martial_status = $request->martial_status;
            $employee->birth_date = $request->birth_date;
            $employee->department = $request->department;
            $employee->hiring_date = $request->hiring_date;
            $employee->daily_salary = $request->daily_salary;
            $employee->working_hours = $request->working_hours;
            $employee->job_title = $request->job_title;
            $employee->telephone = $request->telephone;
            $employee->mobile = $request->mobile;
            $employee->can_not_use_program = $request->can_not_use_program;
            $employee->is_active = $request->is_active;
            $employee->borrow_system = $request->borrow_system;
            $employee->save();

            $role = $user->roles->first();
            $role->detachPermissions($role->permissions);
        
            /* attatch permissions to role */
            if($request->permissions){
               foreach ($request->permissions as $permission) {
                   $role->attachPermission(Permission::find($permission));
               } 
            }
            
            return redirect()->back()
                ->with('success', 'تم تعديل بيانات الموظف.');
        }
    }
}