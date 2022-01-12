@extends('layouts.app')

@section('title', 'إصدار شيك وارد')

@section('form-header', "إصدار شيك وارد - {$bankName}")

@section('content')
    <style>
        .handle-checkDelete {}
        .handle-depositValue {}
        .handle-withdrawValue { display: none; }
        .handle-recordDesc {}
        .handle-cbo_processes {}
        .handle-client_id {}
        .handle-supplier_id { display: none; }
        .handle-employee_id { display: none; }
        .handle-expenses_id { display: none; }
        .handle-cashing_date {}
        .handle-cheque_number {}
        .handle-cheque_status {}
        .handle-track-user-log {}
    </style>
    <!-- /.row -->
    @include('cash.journal._bank-cash-form')
@endsection