<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'DashboardController@index');
//Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
//Route::get('/dashboard/store', 'DashboardController@store');


Route::resource('dashboard', 'DashboardController');

Route::post('/depositwithdraw/LockSaveToAll', 'DepositWithdrawController@LockSaveToAll');
Route::post('/depositwithdraw/RemoveSelected', 'DepositWithdrawController@RemoveSelected');
Route::get('/depositwithdraw/search', 'DepositWithdrawController@search')->name('depositwithdraw.search');
Route::resource('/depositwithdraw', 'DepositWithdrawController');

Route::resource('/facility', 'FacilityController');

//Employees
Route::get('/employee/trash', 'EmployeeController@trash')->name('employee.trash');
Route::get('/employee/restore/{id}', 'EmployeeController@restore')->name('employee.restore');
Route::resource('/employee', 'EmployeeController');


//3amalyat el 3amel
Route::get('/client/process/trash', 'ClientProcessController@trash')->name('client.trash');
Route::get('/client/process/restore/{id}', 'ClientProcessController@restore')->name('client.process.restore');
Route::resource('client/process', 'ClientProcessController');

//3amalyat el mowared
Route::get('/supplier/process/trash', 'SupplierProcessController@trash')->name('supplier.trash');
Route::get('/supplier/process/restore/{id}', 'SupplierProcessController@restore')->name('supplier.process.restore');
Route::resource('supplier/process', 'SupplierProcessController');

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

//api
Route::group(['prefix' => 'api'], function() {
    Route::get('getClientProcesses', 'ClientProcessController@getClientProcesses');
    Route::get('getSupplierProcesses', 'SupplierProcessController@getSupplierProcesses');
});

//reports
Route::group(['prefix' => 'reports'], function() {
    Route::get('/', 'ReportsController@index');
    Route::get('client/', 'ReportsController@client');
    Route::get('client/viewClientReport', 'ReportsController@viewClientReport');
    Route::get('client/getClientProcesses', 'ClientProcessController@getClientReportProcesses');
});

