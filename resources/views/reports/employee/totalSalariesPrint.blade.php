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
                        <td class="monthLabel">مرتبات شهر :</td> 
                        <td class="monthName">{{ $monthName }}</td>
                    </tr>
                </table>
            </div>
            <table class="tg">
                <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>عدد الساعات الفعلى</th>
                        <th>سعر الساعة</th>
                        <th>المرتب</th>
                        <th>خصومات الغياب</th>
                        <th>السلف المستديمة</th>
                        <th>سلف يومية</th>
                        <th>خصم مسبب</th>
                        <th>اجمالى السلف</th>
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
            <div class="lineBreak"></div>
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