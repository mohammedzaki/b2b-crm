@extends("layouts.app") 
@section("title", "تقرير عملية عميل - التقارير")
@section("content")

<div class="row">
    <form role="form" action="printTotalPDF" method="GET">
        <div class="col-lg-12" id="printcontent">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12 no-padding">
                        <div class="col-lg-12 no-padding">
                            <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;" />
                        </div>
                        <div class="col-lg-6 no-padding">
                            <div class="form-group">
                                <label>اسم العميل :</label> {{ $clientName }}
                            </div>
                        </div>
                        <div class="col-lg-12 no-padding">
                            <div class="panel-body">

                                <div class="dataTable_wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>

                                                    <th>اسم العملية</th>
                                                    <th>الاجمالى</th>
                                                    <th>المدفوع</th>
                                                    <th>المتبقى</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($proceses as $process)
                                                <tr class="odd">
                                                    <td>{{ $process['processName'] }}</td>
                                                    <td>{{ $process['processTotalPrice'] }}</td>
                                                    <td style="color: red;">{{ $process['processTotalPaid'] }}</td>
                                                    <td>{{ $process['processTotalRemaining'] }}</td>
                                                </tr>
                                                @empty
                                                @endforelse
                                                <tr class="info">
                                                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                                    <td style="color: red;">{{ $allProcessesTotalPrice }}</td>
                                                    <td style="color: red;">{{ $allProcessTotalPaid }}</td>
                                                    <td style="color: red;">{{ $allProcessTotalRemaining }}</td>
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
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

        <row class="col-lg-12 clearboth"> 
            <p class="text-center">
                {{ Form::hidden("ch_detialed", "0", null) }} 
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
    </form>
</div>

@endsection