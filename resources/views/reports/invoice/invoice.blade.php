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
        <div class="invoice">
            <div class="invoiceHeader">
<!--                <table class="headerTable">
                    <tr>
                        <td ></td>
                        <td ></td>
                    </tr>
                </table>-->
                <span class="invoiceNoLabel">فاتورة <span class="invoiceNo">00000001</span></span>
            </div>
            <div class="clientDetails">
                <table class="headerTable">
                    <tr>
                        <td class="employeeLabel">مطلوب من /</td>
                        <td class="employeeName"> {{ $clinetName }} </td>
                    </tr>
                </table>
            </div>
            <div class="itemsTbl">
                <table>
                    @for ($i = 0; $i < 17; $i++)
                    @if ($i == 0)
                    <tr class="first-child">
                        <td class="first-child">Total Price <br> السعر الاجمالي</td>
                        <td>Unit Price <br> سعر الوحدة</td>
                        <td>Qty <br> الكمية</td>
                        <td>Size <br> المقاس</td>
                        <td>Description <br> البيان</td>
                    </tr>
                    @elseif ($i == 16)
                    <tr class="last-child">
                        <td class="first-child">{{ $invoiceItems[$i]["total_price"] }}</td>
                        <td>{{ $invoiceItems[$i]["unit_price"] }}</td>
                        <td>{{ $invoiceItems[$i]["quantity"] }}</td>
                        <td>{{ $invoiceItems[$i]["size"] }}</td>
                        <td>{{ $invoiceItems[$i]["description"] }}</td>
                    </tr>
                    @else
                    <tr>
                        <td class="first-child">{{ $invoiceItems[$i]["total_price"] }}</td>
                        <td>{{ $invoiceItems[$i]["unit_price"] }}</td>
                        <td>{{ $invoiceItems[$i]["quantity"] }}</td>
                        <td>{{ $invoiceItems[$i]["size"] }}</td>
                        <td>{{ $invoiceItems[$i]["description"] }}</td>
                    </tr>
                    @endif
                    @endfor
                </table>
            </div>
            <div class="footerTbl">
                <table>
                    <tr>
                        <td><div>{{ $totalPriceAfterTaxes }}</div></td>
                        <td colspan="4"><div><span>فقط وقدره : <span>{{ $arabicPriceAfterTaxes }}</span></span></div></td>
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
