@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">لوحة التحكم</h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa  fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['clients_number'] }}</div>
                        <div>عملاء</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('client') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['suppliers_number'] }}</div>
                        <div>موردين</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('supplier') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['process_number'] }}</div>
                        <div>عمليات العملاء</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('client/process') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['Supplierprocess_number'] }}</div>
                        <div>عمليات الموردين</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('supplier/process') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                وارد / منصرف
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                {{ Form::open(['route' => 'dashboard.store']) }}
                @include('_form')
                {{ Form::close() }}

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<script>
    $(document).ready(function ($) {
        $('#client_id').change(function () {
            $.get("{{ url('api/getClientProcesses/') }}", {option: $(this).val()},
                    function (data) {
                        var clientprocesses = $('#cbo_processes');
                        clientprocesses.empty();
                        $.each(data, function (key, value) {
                            clientprocesses.append($("<option></option>").attr("value", key).text(value));
                        });
                    });
        });
        $('#supplier_id').change(function () {
            $.get("{{ url('api/getSupplierProcesses/') }}", {option: $(this).val()},
                    function (data) {
                        var clientprocesses = $('#cbo_processes');
                        clientprocesses.empty();
                        $.each(data, function (key, value) {
                            clientprocesses.append($("<option></option>").attr("value", key).text(value));
                        });
                    });
        });
    });
</script>
@endsection

