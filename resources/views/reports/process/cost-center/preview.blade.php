@extends($reportLayout)
@section('reportTitle', 'تقرير مركز التكلفة')
@section("title", "تقرير مركز التكلفة - التقارير")
@section('styles')
    <link href="/{{$reportStyle}}" rel="stylesheet">
@endsection
@section('reportHeader')
    <div class="col-lg-12 no-padding">
        <table align="center">
            <tr>
                <td width="30%">
                    <div>
                        <label> اسم العميل :</label> {{ $clientName }}
                    </div>
                </td>
                <td width="40%">
                    <div>
                        <label> تاريخ :</label> {{ $date }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
@section('reportHTML')
    @forelse ($processes as $process)
        <div class="col-lg-12 no-padding">
            <div class="col-lg-6 no-padding">
                <label>اسم العملية :</label> {{ $process['processName'] }}
            </div>
            <div class="col-lg-6 no-padding">
                <label>مسلسل :</label> {{ $process['processNum'] }}
            </div>
            <div class="col-lg-6 no-padding">
                <label>تاريخ :</label> {{ $process['processDate'] }}
            </div>
            <div class="col-lg-12 no-padding">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        المصروفات
                    </div>
                    <div class="panel-body">

                        <div class="dataTable_wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>بيان</th>
                                        <th>التاريخ</th>
                                        <th>الاجمالى</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($process['processExpenses'] as $expense)
                                        <tr class="odd @if($expense['pending']) isPending @endif">
                                            <td> {{ $expense['desc'] }} </td>
                                            <td> {{ $expense['date'] }} </td>
                                            <td style="color: red;"> {{ $expense['totalCost'] }} </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    <tr class="info">
                                        <td colspan="2">الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                        <td style="color: red;"> {{ $process['totalProcessExpenses'] }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 no-padding">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        الموردين
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>اسم المورد</th>
                                        <th>بيان</th>
                                        <th>التاريخ</th>
                                        <th>الاجمالى</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($process['processSuppliers'] as $supplier)
                                        <tr class="odd">
                                            <td> {{ $supplier['name'] }} </td>
                                            <td> {{ $supplier['desc'] }} </td>
                                            <td> {{ $supplier['date'] }} </td>
                                            <td style="color: red;"> {{ $supplier['totalCost'] }} </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    <tr class="info">
                                        <td colspan="3">الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                        <td style="color: red;"> {{ $process['totalProcessSuppliers'] }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 no-padding">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        الموظفين
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>اسم الموظف</th>
                                        <th>عدد الساعات</th>
                                        <th>عدد الايام</th>
                                        <th>سعر الساعة</th>
                                        <th>الاجمالى</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($process['manpowerHoursCost'] as $manpower)
                                        <tr class="odd">
                                            <td> {{ $manpower['name'] }} </td>
                                            <td> {{ $manpower['totalHours'] }} </td>
                                            <td> {{ $manpower['totalDays'] }} </td>
                                            <td> {{ $manpower['hourRate'] }} </td>
                                            <td style="color: red;"> {{ $manpower['totalCost'] }} </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    <tr class="info">
                                        <td  colspan="4">الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                        <td style="color: red;"> {{ $process['totalManpowerHoursCost'] }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 no-padding">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        مصروفات الشركة
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>اسم المصروف</th>
                                        <th>عدد الايام</th>
                                        <th>الاجمالى</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($process['companyExpenses'] as $companyExpense)
                                        <tr class="odd">
                                            <td> {{ $companyExpense['name'] }} </td>
                                            <td> {{ $companyExpense['totalDays'] }} </td>
                                            <td style="color: red;"> {{ $companyExpense['totalCost'] }} </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    <tr class="info">
                                        <td colspan="2">الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                        <td style="color: red;"> {{ $process['totalCompanyExpenses'] }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
@endsection