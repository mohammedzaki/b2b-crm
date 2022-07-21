@extends('layouts.app')

@section('title', 'إصدار شيك وارد')

@if(empty($bankId))
    @section('form-header', "إصدار شيك وارد")
@else
    @section('form-header', "إصدار شيك وارد - {$bankName}")
@endif

@section('select-bank')
    @include('common.select-bank', ['formConfig' => ['method' => 'GET', 'route' => ['bank-cash.depositChequeBook', ''], 'id' => 'SearchForm']])
@endsection

@section("styles-l2")
    <style>
        .cell.handle-checkDelete {}
        .cell.handle-depositValue { width: 120px; }
        .cell.handle-withdrawValue { display: none; }
        .cell.handle-recordDesc { width: 120px; }
        .cell.handle-cbo_processes {}
        .cell.handle-client_id {}
        .cell.handle-supplier_id { display: none; }
        .cell.handle-employee_id { display: none; }
        .cell.handle-expenses_id { display: none; }
        .cell.handle-issuing_date {}
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

@section('scripts-l2')
    <script type="text/javascript">
        document.querySelector('th.cell.handle-cashing_date').innerHTML = "تاريخ التحصيل";
    </script>
@endsection