@extends('layouts.app')

@section('title', 'دفتر الشيكات')

@if(empty($bankId))
    @section('form-header', "عرض دفتر شيكات")
@else
    @section('form-header', " عرض دفتر شيكات ({$chequeBookName}) - {$bankName}")
@endif

@section('select-bank')
    @include('common.select-bank', ['formConfig' => ['method' => 'GET', 'route' => 'bank-profile.chequeBook', 'id' => 'SearchForm']])
@endsection

@php
    $canAddRow = 'false';
@endphp

@section("styles-l2")
    <style>
        .cell.handle-checkDelete {}
        .cell.handle-depositValue { display: none; }
        .cell.handle-withdrawValue {}
        .cell.handle-recordDesc {}
        .cell.handle-cbo_processes {}
        .cell.handle-client_id {}
        .cell.handle-supplier_id {}
        .cell.handle-employee_id {}
        .cell.handle-expenses_id {}
        .cell.handle-cashing_date {}
        .cell.handle-cheque_number {}
        .cell.handle-cheque_status {}
        .cell.handle-track-user-log {}
    </style>
@endsection

@section('content')
    <!-- /.row -->
    @include('cash.journal._bank-cash-form')
@endsection