<?php

/*
  |--------------------------------------------------------------------------
  | Reports Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group(['prefix' => 'client'], function() {
    Route::get('/', 'Reports\ClientReportsController@index');
    Route::get('printTotalPDF', 'Reports\ClientReportsController@printTotalPDF');
    Route::get('printDetailedPDF', 'Reports\ClientReportsController@printDetailedPDF');
    Route::any('viewClientReport', 'Reports\ClientReportsController@viewReport')->name('reports.client.viewClientReport');
    
    Route::group(['prefix' => 'ClientAnalyticalCenter'], function() {
        Route::get('/', 'Reports\ClientAnalyticalCenterController@index');
        Route::get('printTotalPDF', 'Reports\ClientAnalyticalCenterController@printTotalPDF');
        Route::get('printDetailedPDF', 'Reports\ClientAnalyticalCenterController@printDetailedPDF');
        Route::any('viewClientReport', 'Reports\ClientAnalyticalCenterController@viewReport')->name('reports.ClientAnalyticalCenter.viewClientReport');
    });
});

Route::group(['prefix' => 'supplier'], function() {
    Route::get('/', 'Reports\SupplierReportsController@index');
    Route::get('printTotalPDF', 'Reports\SupplierReportsController@printTotalPDF');
    Route::get('printDetailedPDF', 'Reports\SupplierReportsController@printDetailedPDF');
    Route::any('viewSupplierReport', 'Reports\SupplierReportsController@viewReport')->name('reports.supplier.viewSupplierReport');
    
    Route::group(['prefix' => 'SupplierAnalyticalCenter'], function() {
        Route::get('/', 'Reports\SupplierAnalyticalCenterController@index');
        Route::get('printTotalPDF', 'Reports\SupplierAnalyticalCenterController@printTotalPDF');
        Route::get('printDetailedPDF', 'Reports\SupplierAnalyticalCenterController@printDetailedPDF');
        Route::any('viewSupplierReport', 'Reports\SupplierAnalyticalCenterController@viewReport')->name('reports.SupplierAnalyticalCenter.viewSupplierReport');
    });
});
