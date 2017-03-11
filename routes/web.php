<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::auth();

Route::get('/', 'DashboardController@index');
Route::get('/home', 'DashboardController@index');

Route::resource('dashboard', 'DashboardController');

Route::post('/depositwithdraw/LockSaveToAll', 'DepositWithdrawController@LockSaveToAll');
Route::post('/depositwithdraw/RemoveSelected', 'DepositWithdrawController@RemoveSelected');
Route::get('/depositwithdraw/search', 'DepositWithdrawController@search')->name('depositwithdraw.search');
Route::resource('/depositwithdraw', 'DepositWithdrawController');

Route::resource('/facility', 'FacilityController');
Route::resource('/facilityopeningamount', 'OpeningAmountController');

//Employees
Route::get('/employee/trash', 'EmployeeController@trash')->name('employee.trash');
Route::get('/employee/restore/{id}', 'EmployeeController@restore')->name('employee.restore');
Route::resource('/employee', 'EmployeeController');


//3amalyat el 3amel
Route::get('/client/process/trash', 'ClientProcessController@trash')->name('client.trash');
Route::get('/client/process/restore/{id}', 'ClientProcessController@restore')->name('client.process.restore');
Route::resource('client/process', 'ClientProcessController', ['as' => 'client']);

//3amalyat el mowared
Route::get('/supplier/process/trash', 'SupplierProcessController@trash')->name('supplier.trash');
Route::get('/supplier/process/restore/{id}', 'SupplierProcessController@restore')->name('supplier.process.restore');
Route::resource('supplier/process', 'SupplierProcessController', ['as' => 'supplier']);

//3amel
Route::get('/client/trash', 'ClientController@trash')->name('client.trash');
Route::get('/client/restore/{id}', 'ClientController@restore')->name('client.restore');
Route::resource('client', 'ClientController');

//mowared
Route::get('/supplier/trash', 'SupplierController@trash')->name('supplier.trash');
Route::get('/supplier/restore/{id}', 'SupplierController@restore')->name('supplier.restore');
Route::resource('supplier', 'SupplierController');

//masrouf
Route::resource('expenses', 'ExpensesController');

//salafeyat
Route::resource('employeeBorrow', 'EmployeeBorrowController');

Route::get('/attendance/checkin', 'AttendanceController@checkin')->name('attendance.checkin');
Route::get('/attendanceSearch/{employee_id}', 'AttendanceController@search')->name('attendance.search');
Route::get('/attendance/checkout', 'AttendanceController@checkout')->name('attendance.checkout');
Route::get('/attendance/manualadding', 'AttendanceController@manualadding')->name('attendance.manualadding');
Route::get('/attendance/guardianship/{employee_id}', 'AttendanceController@guardianship')->name('attendance.guardianship');
Route::get('/attendance/guardianshipaway/{employee_id}', 'AttendanceController@guardianshipaway')->name('attendance.guardianshipaway');
Route::get('/attendance/printSalaryReport/{employee_id}', 'AttendanceController@printSalaryReport')->name('attendance.printSalaryReport');
Route::post('/attendance/paySalary/{employee_id}', 'AttendanceController@payEmpolyeeSalary')->name('attendance.payEmpolyeeSalary');

Route::resource('attendance', 'AttendanceController');

Route::resource('feasibilityStudy', 'FeasibilityStudyController');

Route::resource('invoice', 'InvoiceController');

//reports
/*
Route::group(['prefix' => 'reports'], function() {

    Route::group(['prefix' => 'client'], function() {
        Route::get('/', 'Reports\ClientReportsController@index');
        Route::get('printTotalPDF', 'Reports\ClientReportsController@printTotalPDF');
        Route::get('printDetailedPDF', 'Reports\ClientReportsController@printDetailedPDF');
        Route::any('viewClientReport', 'Reports\ClientReportsController@viewReport')->name('reports.client.viewClientReport');
    });

    Route::group(['prefix' => 'supplier'], function() {
        Route::get('/', 'Reports\SupplierReportsController@index');
        Route::get('printTotalPDF', 'Reports\SupplierReportsController@printTotalPDF');
        Route::get('printDetailedPDF', 'Reports\SupplierReportsController@printDetailedPDF');
        Route::any('viewSupplierReport', 'Reports\SupplierReportsController@viewReport')->name('reports.supplier.viewSupplierReport');
    });
});*/
