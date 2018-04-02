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
