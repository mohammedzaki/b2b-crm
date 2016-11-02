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
Route::get('/mass', function () {
    return view('mass');
});

Route::auth();

Route::get('/', 'DashboardController@index');
// Route::get('/facilities/info', 'FacilityController@index');
Route::resource('facility', 'FacilityController');
Route::resource('employee', 'EmployeeController');


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
Route::resource('supplier', 'SupplierController');

//masrouf
Route::resource('expenses', 'ExpensesController');

//salafeyat
Route::resource('employee/borrow', 'EmployeeBorrowController');

/**
 * Ajax Routes
 */
// Route::get('/getManagerList', 'AjaxController@getManagerList');
Route::get('api/getClientProcesses', 'SupplierProcessController@getClientProcesses');