@extends('layouts.app')

@section('title', 'إصدار شيك منصرف')

@section('form-header', "إصدار شيك منصرف - {$bankName}")

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