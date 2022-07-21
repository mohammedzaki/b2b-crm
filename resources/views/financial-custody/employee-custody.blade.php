@extends('layouts.app')

@section('title', 'كشف حساب العهد')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">كشف حساب العهد</h1>
        </div>
    </div>
    @include('common.select-employee', ['formConfig' => ['method' => 'GET', 'route' => ['financialCustody.employeeCustody', ''], 'id' => 'SearchForm']])

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    كشف حساب العهد
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">

                        <div class="table-responsive">
                            <table width="2000" class="table table-striped table-bordered table-hover"
                                   id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>م</th>
                                    <th>عهدة</th>
                                    <th>رد عهدة</th>
                                    <th>بيان</th>
                                    <th>التاريخ</th>
                                    <th>عهدة مرحلة</th>
                                    <th>مصروفات العهدة</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($employeeFinancialCustodys as $index => $financialCustody)
                                    <tr class="odd">
                                        <td hidden>{{ $financialCustody->id }}</td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $financialCustody->withdrawValue }}</td>
                                        <td>{{ $financialCustody->depositValue }}</td>
                                        <td>{{ $financialCustody->recordDesc }}</td>
                                        <td>{{ $financialCustody->due_date }}</td>
                                        <td>{{ $financialCustody->notes }}</td>
                                        <td>
                                            {{ link_to_route('financialCustodyItems.index', 'عرض/تعديل', ['employee_id' => $financialCustody->employee_id, 'id' => $financialCustody->id], array('class' => 'btn btn-primary')) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>ﻻ يوجد بيانات.</tr>
                                @endforelse
                                <tr>
                                    <th></th>
                                    <th>{{ $totalFinancialCustodyValue }}</th>
                                    <th>{{ $totalFinancialCustodyRefundValue }}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>

@endsection