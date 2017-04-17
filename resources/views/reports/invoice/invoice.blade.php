<!DOCTYPE html>
<html>
    <head>
        <link href="{{ url('ReportsHtml/Invoice/Invoice.css') }}" rel="stylesheet">
    </head>
    <body>

    <htmlpageheader name="invoicePageheader" class="invoicePageheader">
        <div class="header">
            <img src="var:letrImg">
        </div>
    </htmlpageheader>
    <sethtmlpageheader name="invoicePageheader" value="on" show-this-page="1"> </sethtmlpageheader>

    <div class="invoice">
        <div class="invoiceHeader">
            <span class="invoiceNoLabel">فاتورة <span class="invoiceNo">{{ $invoiceNo }}</span></span>
        </div>
        <div class="clientDetails">
            <table class="headerTable">
                <tr>
                    <td class="clientLabel">مطلوب من /</td>
                    <td class="clientName"> {{ $clinetName }} </td>
                    <td class="invoice-date-sep"></td>
                    <td class="invoice-date-lbl" >بتاريخ /</td>
                    <td class="invoice-date">{{ $invoiceDate }}</td>
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

    <htmlpagefooter name="invoicePageFooter" class="invoicePageFooter">
        <div class="footer">
            <img src="var:footerImg">
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="invoicePageFooter" value="on" show-this-page="1"> </sethtmlpagefooter>

</body>
</html>
