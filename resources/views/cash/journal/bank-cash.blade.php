@extends('layouts.app')

@section('title', 'وارد / منصرف بنك')

@if(empty($bankId))
    @section('form-header', "وارد / منصرف بنك")
@else
    @section('form-header', "وارد / منصرف بنك - {$bankName}")
@endif
@section('select-bank')
    @include('common.select-bank', ['formConfig' => ['method' => 'GET', 'route' => ['bank-cash.index', ''], 'id' => 'SearchForm']])
@endsection

@section("styles-l2")
    <style>
        .cell.handle-checkDelete {}
        .cell.handle-depositValue {}
        .cell.handle-withdrawValue {}
        .cell.handle-recordDesc {}
        .cell.handle-cbo_processes {}
        .cell.handle-client_id {}
        .cell.handle-supplier_id {}
        .cell.handle-employee_id {}
        .cell.handle-expenses_id {}
        .cell.handle-issuing_date {}
        .cell.handle-cashing_date {}
        .cell.handle-cheque_number {}
        .cell.handle-cheque_status {}
        .cell.handle-cheque_notes {}
        .cell.handle-track-user-log {}
    </style>
@endsection

@section('content')
    <!-- /.row -->
    @include('cash.journal._bank-cash-form')
@endsection