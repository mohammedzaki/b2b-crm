@extends("layouts.app") 
@section("title", "تقرير المصروفات - التقارير")
@section("content")

<div class="row">
    {{ Form::open(["route" => "reports.expenses.printTotalPDF", "method" => "GET"]) }} 
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
                            <label>المركز التحليلى للعملاء</label>
                        </div>
                    </div>
                    <div class="col-lg-12 no-padding">
                        <div class="panel-body">

                            <div class="dataTable_wrapper">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>اسم المصروف</th>
                                                <th>المدفوع</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($expenses as $expense)
                                            <tr class="odd">
                                                <td>{{ $expense['expenseName'] }}</td>
                                                <td style="color: red;">{{ $expense['expenseTotalPaid'] }}</td>
                                            </tr>
                                            @empty
                                            @endforelse
                                            <tr class="info">
                                                <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                                <td style="color: red;">{{ $allExpensesTotalPaid }}</td>
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
    {{ Form::close() }} 
</div>

@endsection