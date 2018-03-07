@extends("layouts.app") 
@section("title", "تقرير المصروفات - التقارير")
@section("content")
<div class="row">

    {{ Form::open(['method' => 'GET', 'route' => 'invoice.printPreviewPDF', 'style' => 'display: inline-block;']) }}
    <div class="col-lg-12" id="printcontent">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                    <div class="col-lg-12">
                        <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;" />
                    </div>


                    <div class="col-lg-12">
                        <div class="invoiceHeader" style="text-align: center; font-size: 30px; margin-bottom: 20px;">
                            <span class="invoiceNoLabel">فاتورة <span class="invoiceNo">{{ $invoiceNo }}</span></span>
                        </div>
                    </div>

                    <div class="col-lg-12" style="margin-bottom: 10px; font-size: 20px;">
                        <div class="col-lg-6">مطلوب من / {{ $clinetName }}</div>

                        <div class="col-lg-6">بتاريخ / {{ $invoiceDate }}</div>
                    </div>

                    <div class="col-lg-12">
                        <div class="dataTable_wrapper">
                            <div class="table-responsive">
                                <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th class="first-child total-price">Total Price <br> السعر الاجمالي</th>
                                            <th class="unit-price" >Unit Price <br> سعر الوحدة</th>
                                            <th class="qty">Qty <br> الكمية</th>
                                            <th class="size">Size <br> المقاس</th>
                                            <th class="desc">Description <br> البيان</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($invoiceItems); $i++)

                                        <tr>
                                            <td>{{ $invoiceItems[$i]["total_price"] }}</td>
                                            <td>{{ $invoiceItems[$i]["unit_price"] }}</td>
                                            <td>{{ $invoiceItems[$i]["quantity"] }}</td>
                                            <td>{{ $invoiceItems[$i]["size"] }}</td>
                                            <td>{{ $invoiceItems[$i]["description"] }}</td>
                                        </tr>
                                        @endfor
                                        <tr class="info">
                                            <td>{{ $totalPriceAfterTaxes }}</td>
                                            <td>فقط وقدره :</td>
                                            <td colspan="3">{{ $arabicPriceAfterTaxes }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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