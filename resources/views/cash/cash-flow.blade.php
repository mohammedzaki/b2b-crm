@extends('layouts.app')

@section('title', 'شاشة الارصدة')


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">شاشة الارصدة</h1>
        </div>
    </div>

    <!-- /.row -->
    <div class="row">

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
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    دفتر الشيكات
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body operationdes">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>اسم الخزينة</th>

                                <th>الوارد</th>
                                <th>الشيكات الواردة</th>

                                <th>المنصرف</th>
                                <th>الشيكات المنصرفة</th>

                                <th>الرصيد الحالى</th>

                                <th>الشيكات الواردة تحت التحصيل</th>
                                <th>الشيكات المنصرفة تحت التحصيل</th>

                                <th>معادلة الارصدة</th>
                            </tr>
                            </thead>
                            <tbody id="prcoess_items">
                            @forelse ($cashItems as $item)
                                <tr role="row">
                                    <td>{{ $item['name'] }}</td>

                                    <td>{{ $item['deposit'] }}</td>
                                    <td>{{ $item['depositCheques'] }}</td>

                                    <td>{{ $item['withdraw'] }}</td>
                                    <td>{{ $item['withdrawCheques'] }}</td>

                                    <td>{{ $item['currentAmount'] }}</td>

                                    <td>{{ $item['postdatedDepositCheques'] }}</td>
                                    <td>{{ $item['postdatedWithdrawCheques'] }}</td>

                                    <td>{{ $item['cashBalance'] }}</td>
                                </tr>
                            @empty
                                <tr>ﻻ يوجد شيكات.</tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                                <tr role="row">
                                    <td>الاجمالي</td>

                                    <td>{{ $sumDeposit }}</td>
                                    <td>{{ $sumDepositCheques }}</td>

                                    <td>{{ $sumWithdraw }}</td>
                                    <td>{{ $sumWithdrawCheques }}</td>

                                    <td>{{ $sumCurrentAmount }}</td>

                                    <td>{{ $sumPostdatedDepositCheques }}</td>
                                    <td>{{ $sumPostdatedWithdrawCheques }}</td>

                                    <td>{{ $sumCashBalance }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

    </div>
@endsection