@extends('layouts.app')

@section('title', 'دفتر الشيكات')

@section('form-header', " عرض دفتر شيكات ({$chequeBookName}) - {$bankName}")

@php
    $canAddRow = 'false';
@endphp

@section('content')
    <style>
        .handle-checkDelete {}
        .handle-depositValue { display: none; }
        .handle-withdrawValue {}
        .handle-recordDesc {}
        .handle-cbo_processes {}
        .handle-client_id {}
        .handle-supplier_id {}
        .handle-employee_id {}
        .handle-expenses_id {}
        .handle-cashing_date {}
        .handle-cheque_number {}
        .handle-cheque_status {}
        .handle-track-user-log {}
    </style>
    <!-- /.row -->
    @include('cash.journal._bank-cash-form')
@endsection