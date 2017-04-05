<!DOCTYPE html>
<html>
    <head>
        <link href="{{ url('ReportsHtml/Invoice/Invoice.css') }}" rel="stylesheet">
    </head>
    <body>
        <!--mpdf
        <htmlpageheader name="myheader">
        </htmlpageheader>
        <sethtmlpageheader name="myheader" value="on" />
        mpdf-->
        <div class="header">
            <img src="var:letrImg">
        </div>
        <div class="clientProcess">
            <div class="processHeader">
                <table class="headerTable">
                    <tr>
                        <td class="employeeLabel">مطلوب من /</td>
                        <td class="employeeName"> {{ $employeeName }} </td>
                    </tr>
                </table>
            </div>
            <div class="itemsTbl">
                <table>
                    <tr>
                        
                        <td class="first-child"><div>Total Price <br> السعر الاجمالي</div></td>
                        <td><div>Unit Price <br> سعر الوحدة</div></td>
                        <td><div>Qty <br> الكمية</div></td>
                        <td><div>Size <br> المقاس</div></td>
                        <td><div>Description <br> البيان</div></td>
                    </tr>

                    <tr>
                        <td class="first-child">100,000.55</td>
                        <td>5000</td>
                        <td>100</td>
                        <td>166</td>
                        <td> لبيليل ليلي ليبل يبليبل يلي</td>
                    </tr>
                    <tr>
                        <td class="first-child">100,000.55</td>
                        <td>5000</td>
                        <td>100</td>
                        <td>166</td>
                        <td> لبيليل ليلي ليبل يبليبل يلي</td>
                    </tr>
                    <tr>
                        <td class="first-child">100,000.55</td>
                        <td>5000</td>
                        <td>100</td>
                        <td>166</td>
                        <td> لبيليل ليلي ليبل يبليبل يلي</td>
                    </tr>
                    <tr>
                        <td class="first-child">100,000.55</td>
                        <td>5000</td>
                        <td>100</td>
                        <td>166</td>
                        <td> لبيليل ليلي ليبل يبليبل يلي</td>
                    </tr>
                </table>
            </div>
            <div class="itemsTbl">
                <table>
                    <tr>
                        <td><div>100,000.55</div></td>
                        <td colspan="4"><div><span>فقط وقدره : <span>ثلاثة الالف فقط لاغير</span></span></div></td>
                    </tr>
                </table>
            </div>

        </div>
        <div class="footer" style="">
            <img src="var:footerImg">
        </div>
        <!--mpdf
        <htmlpagefooter name="myfooter">
        </htmlpagefooter>
        <sethtmlpagefooter name="myfooter" value="on" />
        mpdf-->
    </body>
</html>
