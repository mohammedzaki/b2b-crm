@extends("layouts.app") 
@section("title", "تقرير عملية عميل - التقارير")
@section("content")

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
                                                <tr class="odd">
                                                    <td> {{ $expense['desc'] }} </td>
                                                    <td> {{ $expense['date'] }} </td>
                                                    <td style="color: red;"> {{ $expense['total'] }} </td>
                                                </tr>
                                                @empty
                                                @endforelse
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
                                                @forelse ($process['suppliers'] as $supplier)
                                                <tr class="odd">
                                                    <td> {{ $supplier['name'] }} </td>
                                                    <td> {{ $supplier['desc'] }} </td>
                                                    <td> {{ $supplier['date'] }} </td>
                                                    <td style="color: red;"> {{ $supplier['total'] }} </td>
                                                </tr>
                                                @empty
                                                @endforelse
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