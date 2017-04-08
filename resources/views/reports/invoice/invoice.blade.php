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
                        <td class="clientLabel">مطلوب من /</td>
                        <td class="clientName"> {{ $clinetName }} </td>
                        <td class="invoice-date-sep"></td>
                        <td class="invoice-date-lbl" >بتاريخ /</td>
                        <td class="invoice-date">12/03/2014</td>
                    </tr>
                </table>
            </div>
            <div class="itemsTbl">
                <table>
                    @for ($i = 0; $i <= 20; $i++)
                    @if ($i == 0)
                    <tr class="first-child">
                        <td class="first-child total-price">Total Price <br> السعر الاجمالي</td>
                        <td class="unit-price" >Unit Price <br> سعر الوحدة</td>
                        <td class="qty">Qty <br> الكمية</td>
                        <td class="size">Size <br> المقاس</td>
                        <td class="desc">Description <br> البيان</td>
                    </tr>
                    @elseif ($i == 20)
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
            <div class="items-footerTbl">
                <div class="total-price">{{ $totalPriceAfterTaxes }}</div>
                <div class="arabic-price"><span class="lbl">فقط وقدره : <div class="text">{{ $arabicPriceAfterTaxes }}</div></span></div>
                
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
