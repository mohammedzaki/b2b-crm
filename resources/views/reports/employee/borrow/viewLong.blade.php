@extends("layouts.app") 
@section("title", "سلفيات الموظفين المستديمة- التقارير")
@section("content")

<div class="row">
    <form role="form" action="print-pdf" method="GET">
        <div class="col-lg-12" id="printcontent">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12 no-padding">
                        <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;" />
                    </div>
                    @forelse ($employees as $employee)
                    <div class="col-lg-12 no-padding">
                        <div class="col-lg-6 no-padding">
                            <label>اسم الموظف :</label> {{ $employee['employeeName'] }}
                        </div>
                        <div class="col-lg-6 no-padding">
                            <label>مسلسل :</label> {{ $employee['employeeNum'] }}
                        </div>
                        <div class="col-lg-12 no-padding">
                            <div class="panel-body">

                                <div class="dataTable_wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>بيان</th>
                                                    <th>القيمة</th>
                                                    <th>المدفوع</th>
                                                    <th>المتبقى</th>
                                                    <th>تاريخ</th>
                                                    <th>ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($employee['borrowDetails'] as $details)
                                                <tr class="odd">
                                                    <td> {{ $details['desc'] }} </td>
                                                    <td> {{ $details['totalPrice'] }} </td>
                                                    <td style="color: red;"> {{ $details['paid'] }} </td>
                                                    <td> {{ $details['remaining'] }} </td>
                                                    <td> {{ $details['date'] }} </td>
                                                    <td> {{ $details['notes'] }} </td>
                                                </tr>
                                                <tr class="info">
                                                    <td>بيان</td>
                                                    <td>المدفوع</td>
                                                    <td style="color: red;">الحالة</td>
                                                    <td>تاريخ الاستحقاق</td>
                                                    <td>تاريخ الدفع</td>
                                                    <td>ملاحظات</td>
                                                </tr>
                                                @forelse ($details['amounts'] as $amount)
                                                <tr class="odd">
                                                    <td> {{ $amount['desc'] }} </td>
                                                    <td> {{ $amount['paid_amount'] }} </td>
                                                    <td style="color: red;"> {{ $amount['paying_status'] }} </td>
                                                    <td> {{ $amount['due_date'] }} </td>
                                                    <td> {{ $amount['paid_date'] }} </td>
                                                    <td> {{ $amount['notes'] }} </td>
                                                </tr>
                                                @empty
                                                @endforelse
                                                
                                                @empty
                                                @endforelse
                                                <tr class="info">
                                                    <td></td>
                                                    <td style="color: red;">{{ $employee['employeeTotalPrice'] }}</td>
                                                    <td style="color: red;">{{ $employee['employeeTotalPaid'] }}</td>
                                                    <td style="color: red;">{{ $employee['employeeTotalRemaining'] }}</td>
                                                    <td colspan="2" style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                                </tr>
                                            </tbody>
                                        </table>  
                                    </div>
                                    <!-- /.table-responsive -->
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
    </form>
</div>



@endsection