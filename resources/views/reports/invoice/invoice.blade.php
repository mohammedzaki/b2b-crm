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
    <sethtmlpageheader name="invoicePageheader" value="{{ $showLetterHead }}" show-this-page="1"> </sethtmlpageheader>

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
                <tr class="first-child">
                    <td class="first-child total-price">Total Price <br> السعر الاجمالي</td>
                    <td class="unit-price" >Unit Price <br> سعر الوحدة</td>
                    <td class="qty">Qty <br> الكمية</td>
                    <td class="size">Size <br> المقاس</td>
                    <td class="desc">Description <br> البيان</td>
                </tr>
                @for ($i = 0; $i < count($invoiceItems); $i++)
                @if ($i < 19)
                <tr>
                    <td class="first-child {{ $invoiceItems[$i]["class"] }}">{{ $invoiceItems[$i]["total_price"] }}</td>
                    <td>{{ $invoiceItems[$i]["unit_price"] }}</td>
                    <td>{{ $invoiceItems[$i]["quantity"] }}</td>
                    <td>{{ $invoiceItems[$i]["size"] }}</td>
                    <td class="{{ $invoiceItems[$i]["class"] }}">{{ $invoiceItems[$i]["description"] }}</td>
                </tr>
                @else
                <tr class="last-child">
                    <td class="first-child {{ $invoiceItems[$i]["class"] }}">{{ $invoiceItems[$i]["total_price"] }}</td>
                    <td>{{ $invoiceItems[$i]["unit_price"] }}</td>
                    <td>{{ $invoiceItems[$i]["quantity"] }}</td>
                    <td>{{ $invoiceItems[$i]["size"] }}</td>
                    <td class="{{ $invoiceItems[$i]["class"] }}">{{ $invoiceItems[$i]["description"] }}</td>
                </tr>
                @endif
                @endfor
                
                @for ($i = 0; $i <= (19 - count($invoiceItems)); $i++)
                @if ($i < (19 - count($invoiceItems)))
                <tr>
                    <td class="first-child"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @else
                <tr class="last-child">
                    <td class="first-child"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
        <div class="footer hidden">
            @if ($showLetterHead == 'on')
            <img src="var:footerImg">
            @endif
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="invoicePageFooter" value="{{ $showLetterHead }}" show-this-page="1"> </sethtmlpagefooter>

</body>
</html>
