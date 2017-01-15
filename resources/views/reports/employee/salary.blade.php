<!DOCTYPE html>
<html>
    <head>
        <link href="{{ url('ReportsHtml/employee/salary.css') }}" rel="stylesheet">
    </head>
    <body>
        <!--mpdf
                    <htmlpageheader name="myheader">
                    <img src="var:letrImg" class="letrHead">
                    </htmlpageheader>

                    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                    mpdf-->
        <div class="clientProcess">
            <div class="processHeader">
                <table class="headerTable">
                    <tr>
                        <td class="employeeLabel">اسم الموظف :</td>
                        <td class="employeeName"> {{ $employeeName }} </td>
                        <td class="monthLabel">مرتب شهر :</td> 
                        <td class="monthName">يناير</td>
                    </tr>
                </table>
            </div>
            <table class="tg">
                <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>اسم العملية</th>
                        <th>من</th>
                        <th>الى</th>
                        <th>ساعات العمل</th>
                        <th>المكافأت</th>
                        <th>الخصومات</th>
                        <th>الملاحظات</th>
                        <th>السلف</th>
                        <th>العهد</th>
                        <th>العهد المردودة</th>
                        <th>نوع الغياب</th>
                        <th>خصم الغياب</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
                    <tr class="odd">
                        <td>{{ $attendance->employeeName }}</td>
                        <td>{{ $attendance->processName }}</td>
                        <td>{{ $attendance->check_in }}</td>
                        <td>{{ $attendance->check_out }}</td>
                        <td>{{ $attendance->workingHours }}</td>
                        <td>{{ $attendance->mokaf }}</td>
                        <td>{{ $attendance->salary_deduction }}</td>
                        <td>{{ $attendance->notes }}</td>
                        <td>{{ $attendance->borrowValue }}</td>
                        <td>{{ $attendance->GuardianshipValue }}</td>
                        <td>{{ $attendance->GuardianshipReturnValue }}</td>
                        <td>{{ $attendance->absentTypeName }}</td>
                        <td>{{ $attendance->absent_deduction }}</td>
                    </tr>
                    @empty
                    <tr>ﻻ يوجد بيانات.</tr>
                    @endforelse
                    <tr class="last">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>اجمالى</th>
                        <th>{{ $totalWorkingHours }}</th>
                        <th>{{ $totalBonuses }}</th>
                        <th>{{ $totalSalaryDeduction }}</th>
                        <th></th>
                        <th>{{ $totalBorrowValue }}</th>
                        <th>{{ $totalGuardianshipValue }}</th>
                        <th>{{ $totalGuardianshipReturnValue }}</th>
                        <th></th>
                        <th>{{ $totalAbsentDeduction }}</th>
                    </tr>
                </tbody>
            </table>
            <div class="lineBreak"></div>
        </div>
        
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    بيانات الاجر
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table>
                        <tr>
                            <td>
                                <label>عدد الساعات الفعلى</label>
                            </td>
                            <td>
                                <label>سعر الساعة</label>
                            </td>
                            <td>
                                <label>الاجمالى</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ Form::text('HourlyRate', $totalWorkingHours, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $hourlyRate, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $totalHoursSalary, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>خصومات الغياب</label>
                            </td>
                            <td>
                                <label>السلف المستديمة</label>
                            </td>
                            <td>
                                <label>سلف يومية</label>
                            </td>
                            <td>
                                <label>خصم مسبب</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ Form::text('HourlyRate', $totalAbsentDeduction, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $totalLongBorrowValue, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $totalSmallBorrowValue, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $totalSalaryDeduction, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>اجمالى السلف</label>
                            </td>
                            <td>
                                <label>المكافأت</label>
                            </td>
                            <td>
                                <label>المرتب</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ Form::text('HourlyRate', $totalBorrowValue, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $totalBonuses, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('TotalSalary', $totalSalary, array(
                                        "id" => "TotalSalary",
                                        'class' => 'form-control')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>اجمالى العهدة</label>
                            </td>
                            <td>
                                <label>اجمالى رد العهدة</label>
                            </td>
                            <td>
                                <label>الصافى المستحق</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ Form::text('TotalSalary', $totalGuardianshipValue, array(
                                        "id" => "totalGuardianshipValue",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('TotalSalary', $totalGuardianshipReturnValue, array(
                                        "id" => "totalGuardianshipReturnValue",
                                        'class' => 'form-control')) }}
                            </td>
                            <td>
                                {{ Form::text('HourlyRate', $totalNetSalary, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                            </td>
                        </tr>
                    </table>
                    
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

        <!--mpdf
                <htmlpagefooter name="myfooter">
                <div class="reportPageFooterLine" ></div>
                <div class="reportPageFooterText">
                صفحة {PAGENO} من {nb}
                </div>
                </htmlpagefooter>
    
                <sethtmlpagefooter name="myfooter" value="on" />
                mpdf-->
    </body>
</html>
