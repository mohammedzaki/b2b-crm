@extends("layouts.app") 
@section("title", "تقرير المصروفات - التقارير")
@section("content")

<div class="row">
    {{ Form::open(["route" => "reports.employees.totalSalaries.printPDF", "method" => "GET"]) }}    
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
                                <label>تقرير المرتبات شهر {{ $monthName }}</label>
                            </div>
                        </div>
                        <div class="col-lg-12 no-padding">
                            <div class="panel-body">

                                <div class="dataTable_wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>اسم الموظف</th>
                                                    <th>عدد الساعات الفعلى</th>
                                                    <th>سعر الساعة</th>
                                                    <th>المرتب</th>
                                                    <th>خصومات الغياب</th>
                                                    <th>السلف المستديمة</th>
                                                    <th>سلف يومية</th>
                                                    <th>اجمالى السلف</th>
                                                    <th>خصم مسبب</th>
                                                    <th>المكافأت</th>
                                                    <th>اجمالى العهدة</th>
                                                    <th>اجمالى رد العهدة</th>
                                                    <th>الصافى المستحق</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($employees as $employee)
                                                <tr class="odd">
                                                    <td>{{ $employee['name'] }}</td>
                                                    <td>{{ $employee['workingHours'] }}</td>
                                                    <td>{{ $employee['hourRate'] }}</td>
                                                    <td>{{ $employee['salary'] }}</td>
                                                    <td>{{ $employee['absentDeduction'] }}</td>
                                                    <td>{{ $employee['longBorrow'] }}</td>
                                                    <td>{{ $employee['smallBorrow'] }}</td>
                                                    <td>{{ $employee['totalBorrow'] }}</td>
                                                    <td>{{ $employee['salaryDeduction'] }}</td>
                                                    <td>{{ $employee['bonuses'] }}</td>
                                                    <td>{{ $employee['financialCustodyValue'] }}</td>
                                                    <td>{{ $employee['financialCustodyRefundValue'] }}</td>
                                                    <td>{{ $employee['netSalary'] }}</td>
                                                </tr>
                                                @empty
                                                <tr>ﻻ يوجد بيانات.</tr>
                                                @endforelse
                                                <tr class="info">
                                                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                                    <td>{{ $totalWorkingHours }}</td>
                                                    <td>{{ $totalHourlyRate }}</td>
                                                    <td>{{ $totalSalary }}</td>
                                                    <td>{{ $totalAbsentDeduction }}</td>
                                                    <td>{{ $totalLongBorrowValue }}</td>
                                                    <td>{{ $totalSmallBorrowValue }}</td>
                                                    <td>{{ $totalBorrowValue }}</td>
                                                    <td>{{ $totalSalaryDeduction }}</td>
                                                    <td>{{ $totalBonuses }}</td>
                                                    <td>{{ $totalFinancialCustodyValue }}</td>
                                                    <td>{{ $totalFinancialCustodyRefundValue }}</td>
                                                    <td>{{ $totalNetSalary }}</td>
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