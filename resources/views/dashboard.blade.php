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
        @if(Entrust::ability('admin', 'deposit-withdraw'))
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
            @include('_bank-calendar')
        @endif
    </div>
@endsection