@extends("layouts.app") 
@section("title", "تقرير عملية عميل - التقارير")
@section("content")
<style>
    .isPending td{
        background-color: rgba(255, 49, 21, 0.5) !important;
    }
</style>
<div class="row">
    {{ Form::open(["route" => "reports.process.cost-center.print-pdf", "method" => "GET"]) }}
    <div class="col-lg-12" id="printcontent">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-12 no-padding">
                    <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;" />
                </div>
                @forelse ($processes as $process)
                <div class="col-lg-12 no-padding">
                    <div class="col-lg-6 no-padding">
                        <label>اسم العميل :</label> {{ $clientName }}
                    </div>
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
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>

    <row class="col-lg-12 clearboth"> 
        <p class="text-center">
            {{ Form::hidden("ch_detialed", "1", null) }} 
            {{ Form::checkbox("withLetterHead", "1", 1, 
                        array(
                            "id" => "withLetterHead",
                            "class" => "checkbox_show_input"
                        )
                    ) }} 
            {{ Form::label("withLetterHead", "طباعة الليتر هد") }}
            <br>
            <button class="btn btn-primary" type="submit">طباعة</button>
        </p>

    </row>
    {{ Form::close() }}
</div>



@endsection