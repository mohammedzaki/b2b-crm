
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                تقرير عملية عميل
            </div>
            <!-- /.panel-heading -->

            <div class="panel-body">
                <div class="form-group">
                    {{ Form::label("client_id", "اسم العميل") }}
                    {{ Form::select("client_id", $clients, null,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "client_id",
                                                            "onchange" => "GetClientProcess(this)")
                                                        )
                    }}
                </div>
                <div class="legend">
                    {{ Form::checkbox(null, "1", null, 
                        array(
                            "id" => "ch_all",
                            "class" => "checkbox_show_input",
                            "onchange" => "SelectAll(this)"
                        )
                    ) }} 
                    {{ Form::label("ch_all", "الكل") }}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="depositwithdrawTable" role="grid" aria-describedby="dataTables-example_info">
                            <thead>
                                <tr role="row">
                                    <th rowspan="1" colspan="1" style="padding: 8px;">اختيار</th>
                                    <th rowspan="1" colspan="1" >اسم العملية</th>
                                    <th rowspan="1" colspan="1" >اجمالي</th>
                                </tr>
                            </thead>
                            <tbody id="grid_ClientProcess">
                                <!--
                                <tr class="gradeA odd ItemRow" role="row">
                                    <td style="text-align:center; vertical-align: middle;">
                                        {{ Form::checkbox("processChoice", "1", null, 
                                                                array(
                                                                    "class" => "",
                                                                    "id" => "processChoice")
                                                        )
                                        }} 
                                    </td>
                                    <td>
                                        {{ Form::text("processName", null, 
                                                                array(
                                                                    "class" => "form-control", 
                                                                    "id" => "processName",
                                                                    "disabled" => "disabled")
                                                                )
                                        }}
                                    </td>
                                    <td>
                                        {{ Form::text("processTotal", null, 
                                                                array(
                                                                    "class" => "form-control", 
                                                                    "id" => "processTotal",
                                                                    "disabled" => "disabled")
                                                                )
                                        }}
                                    </td>
                                </tr>
                                -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row"> 
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h4>
                                    <span>مبلغ الفاتورة </span>
                                    <span class="price invoice_price">0</span>
                                </h4>
                                <h4>
                                    <span>الخصم </span>
                                    <span class="price discount_price">0</span>
                                </h4>
                                <h4>
                                    <span>الضريبة المضافة </span>
                                    <span class="price taxes_price">0</span>
                                </h4>
                                <h4>
                                    <span>خصم من المنبع </span>
                                    <span class="price source_discount_value">0</span>
                                </h4>
                                <hr>
                                <h4>
                                    <span>القيمة اﻻجمالية </span>
                                    <span class="price total_price">0</span>
                                </h4>
                                <h4>
                                    <span>اجمالى المدفوع </span>
                                    <span class="price total_paid">0</span>
                                </h4>
                                <h4>
                                    <span>المتبقى </span>
                                    <span class="price total_remaining">0</span>
                                </h4>
                            </div>
                        </div>
                        {{ Form::hidden('invoice_price') }}
                        {{ Form::hidden('discount_price') }}
                        {{ Form::hidden('taxes_price') }}
                        {{ Form::hidden('source_discount_value') }}
                        {{ Form::hidden('total_price') }}
                        {{ Form::hidden('total_paid') }}
                        {{ Form::hidden('total_remaining') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::checkbox("withLetterHead", "1", 1, array(
                                "id" => "withLetterHead",
                                "class" => "checkbox_show_input")) 
                        }} 
                        {{ Form::label("withLetterHead", "طباعة الليتر هد") }}
                        <button type="button" class="btn btn-block btn-primary center-block" onclick="invoicePreview()">معاينة طباعة</button>
                        <!--<br>-->
                        <!--<button type="button" class="btn btn-block btn-primary center-block" onclick="myTest()">My Test</button>-->
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-block btn-success center-block" onclick="saveInvoice(0)">اصدار</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-block btn-danger center-block" onclick="saveInvoice(1)">اصدار و تحصيل الان</button>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>

            <!-- /.panel -->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الفاتورة
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body operationdes">
                <div class="row">
                    <div class="col-md-12 no-padding">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>رقم الفاتورة</label>
                                {{ Form::text('invoice_number', isset($model) ? null : '########', array(
                                        "id" => "invoice_number",
                                        'class' => 'form-control',
                                        'readonly',
                                        'placeholder' => '########')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>التاريخ</label>
                                {{ Form::text('invoice_date', null, array(
                                        "id" => "invoice_date",
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => 'ادخل التاريخ')) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>السعر الاجمالى</th>
                                        <th>سعر الوحدة</th>
                                        <th>الكمية</th>
                                        <th>المقاس</th>
                                        <th>بيان</th>
                                        <th>تحكم</th>
                                    </tr>
                                </thead>

                                <tbody id="invoiceItems">
                                    @if(isset($invoiceItems))
                                    @for ($i = 0; $i < count($invoiceItems); $i++)
                                    <tr class="ItemRow" >
                                        {{ Form::hidden("invoiceItems[".$i."][id]") }}
                                        <td>
                                            <div class="form-group{{ $errors->has("items.".$i.".description") ? " has-error" : "" }}">
                                                {{ Form::text("invoiceItems[".$i."][description]", $invoiceItems[$i]["description"], 
                                            array(
                                                "class" => "form-control description", 
                                                "placeholder" => "ادخل تفاصيل البيان")
                                            )
                                                }}
                                                @if ($errors->has("items.".$i.".description"))
                                                <label for="inputError" class="control-label">
                                                    {{ $errors->first("items.".$i.".description") }}
                                                </label>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group{{ $errors->has("items.".$i.".total_price") ? " has-error" : "" }}">
                                                {{ Form::text("invoiceItems[".$i."][total_price]", $invoiceItems[$i]["total_price"], 
                                            array(
                                                "class" => "form-control total_price")
                                            )
                                                }}
                                                @if ($errors->has("items.".$i.".total_price"))
                                                <label for="inputError" class="control-label">
                                                    {{ $errors->first("items.".$i.".total_price") }}
                                                </label>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group{{ $errors->has("items.".$i.".unit_price") ? " has-error" : "" }}">
                                                {{ Form::text("invoiceItems[".$i."][unit_price]", $invoiceItems[$i]["unit_price"], 
                                            array(
                                                "class" => "form-control unit_price", 
                                                "placeholder" => "ادخل سعر الوحدة")
                                            )
                                                }}
                                                @if ($errors->has("items.".$i.".unit_price"))
                                                <label for="inputError" class="control-label">
                                                    {{ $errors->first("items.".$i.".unit_price") }}
                                                </label>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group{{ $errors->has("items.".$i.".quantity") ? " has-error" : "" }}">
                                                {{ Form::text("invoiceItems[".$i."][quantity]", $invoiceItems[$i]["quantity"], 
                                            array(
                                                "class" => "form-control quantity", 
                                                "placeholder" => "ادخل الكمية")
                                            )
                                                }}
                                                @if ($errors->has("items.".$i.".quantity"))
                                                <label for="inputError" class="control-label">
                                                    {{ $errors->first("items.".$i.".quantity") }}
                                                </label>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group{{ $errors->has("items.".$i.".size") ? " has-error" : "" }}">
                                                {{ Form::text("invoiceItems[".$i."][size]", $invoiceItems[$i]["size"], 
                                            array(
                                                "class" => "form-control size", 
                                                "placeholder" => "ادخل المقاس")
                                            )
                                                }}
                                                @if ($errors->has("items.".$i.".size"))
                                                <label for="inputError" class="control-label">
                                                    {{ $errors->first("items.".$i.".size") }}
                                                </label>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div>
                                        </td>
                                    </tr>
                                    @endfor
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>
                            <span>القيمة اﻻجمالية </span>
                            <span class="price invoiceItemsTotalPrice">0</span>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>

@section('scripts')
<script>
    var allProcesses = JSON.parse(he.decode("{{ $clientProcesses }}"));
    console.log(allProcesses);
    var currentClientId = 0;
    var totalPrice = 0;
    var totalPriceTaxes = 0;
    var totalPaid = 0;
    var totalRemaining = 0;
    var discount = 0;
    var sourceDiscount = 0;
    var taxes = 0;
    var CurrentCell, CurrentCellName, CurrentRow, AfterCurrentRow, currentRowIndex, lastRowIndex = -1, rowCount = 1, invoiceItemsCount = 0;

    $(document).delegate(".unit_price, .quantity, .total_price, .description, .size", "change", function (e) {
        SetCurrentRowIndex(this);
        var quantity = $('#invoiceItems tr:eq(' + currentRowIndex + ') .quantity');
        ;
        var unit_price = $('#invoiceItems tr:eq(' + currentRowIndex + ') .unit_price');
        var total_price = $('#invoiceItems tr:eq(' + currentRowIndex + ') .total_price');

        if (quantity.val() == "") {
            quantity.val(0);
        }
        if (unit_price.val() == "") {
            unit_price.val(0);
        }
        var pr = parseFloat(unit_price.val()) * parseFloat(quantity.val());
        pr = roundDecimals(pr, 3);
        total_price.val(pr);
        updateInvoicePrices();

        AddNewRow(this);
    });

    $(document).delegate("#invoiceItems .delete", "click", function (e) {
        SetCurrentRowIndex(this);
        RemoveRowAtIndex(currentRowIndex);
        updateInvoicePrices();
    });

    $(function () {
        invoiceItemsCount = $("#invoiceItems").children().length;
        if (invoiceItemsCount == 0) {
            AddNewRow(this);
        } else {
            for (var i = 0; i < invoiceItemsCount; i++) {
                var qty = parseFloat($('input[name="items[' + i + '][quantity]"]').val());
                var unit = parseFloat($('input[name="items[' + i + '][unit_price]"]').val());
                $('input[name="items[' + i + '][total_price]"]').val(roundDecimals((qty * unit), 3));
            }
        }
        updateInvoicePrices();
    });

    function updateInvoicePrices() {
        var invoiceItemsTotalPrice = $('.invoiceItemsTotalPrice');
        var total_price = 0;
        $('.total_price').each(function () {
            if ($(this).val() !== '')
                total_price = parseFloat($(this).val()) + total_price;
        });
        total_price = roundDecimals(total_price, 3);
        invoiceItemsTotalPrice.text(total_price);
    }

    function SetCurrentRowIndex(CellChildInput) {
        CurrentCell = $(CellChildInput).parent().parent();
        CurrentCellName = $(CellChildInput).attr('name');
        CurrentRow = $(CurrentCell).parent();
        AfterCurrentRow = $(CurrentRow).next();
        currentRowIndex = $(CurrentCell)
                .closest('tr') // Get the closest tr parent element
                .prevAll() // Find all sibling elements in front of it
                .length; // Get their count
        rowCount = $(CurrentCell).closest('tr').parent().children().length;

    }

    function AddNewRow(CellChildInput) {
        SetCurrentRowIndex(CellChildInput);
        if ($(AfterCurrentRow).hasClass("ItemRow") == false) {
            invoiceItemsCount = $("#invoiceItems").children().length;
            var html = '<tr class="ItemRow ">';
            html += '<td><div class="form-group"><input class="form-control total_price" name="invoiceItems[' + invoiceItemsCount + '][total_price]" value="" /></div></td>';
            html += '<td><div class="form-group"><input class="form-control unit_price" name="invoiceItems[' + invoiceItemsCount + '][unit_price]" value="" placeholder="ادخل سعر الوحدة" /></div></td>';
            html += '<td><div class="form-group"><input class="form-control quantity" name="invoiceItems[' + invoiceItemsCount + '][quantity]" value="" placeholder="ادخل الكمية" /></div></td>';
            html += '<td><div class="form-group"><input class="form-control size" name="invoiceItems[' + invoiceItemsCount + '][size]" value="" placeholder="ادخل المقاس" /></div></td>';
            html += '<td><div class="form-group"><input class="form-control description" name="invoiceItems[' + invoiceItemsCount + '][description]" placeholder="ادخل تفاصيل البيان" value=""/></div></td>';
            html += '<td><div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td>';
            html += '</tr>';
            $('#invoiceItems').append(html);
            updateInvoicePrices();
        } else {
            updateInvoicePrices();
        }
    }

    function RemoveRowAtIndex(rowIndex) {
        $('#invoiceItems tr:eq(' + rowIndex + ')').remove();
        reArrangeProcess();
    }

    function invoicePreview() {
        if (parseFloat($('.invoiceItemsTotalPrice').html()) === parseFloat($('.invoice_price').html())) {
            $('#invoiceForm').prop('action', 'preview').submit();
        } else {
            alert('مبلغ الفاتورة لا يساوى القيمة الاجمالية لبيانات الفاتورة');
        }
    }

    function saveInvoice(isPayNow) {
        if (parseFloat($('.invoiceItemsTotalPrice').html()) === parseFloat($('.invoice_price').html())) {
            $('#invoiceForm').append('<input type="hidden" name="isPayNow" value="' + isPayNow + '" />').submit();
        } else {
            alert('مبلغ الفاتورة لا يساوى القيمة الاجمالية لبيانات الفاتورة');
        }
    }

    function myTest() {
        $('#invoiceForm').prop('action', 'test/preview').submit();
    }

    function GetClientProcess(client_id) {
        currentClientId = $(client_id).val();
        FillterProcess();
    }

    function SelectAll(ch_all) {
        var rowsCount = $("#grid_ClientProcess").children().length;
        resetProcessPrices();
        $("#invoiceItems").empty();
        for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
            SelectProcessByIndex(rowIndex, $(ch_all).is(":checked"));
        }
        updateInvoicePrices();
    }

    function FillterProcess() {
        $("#grid_ClientProcess").empty();
        $("#invoiceItems").empty();
        resetProcessPrices();
        var index = 0;
        if (allProcesses[currentClientId] != null) {
            $.each(allProcesses[currentClientId], function (processId, value) {
                $("#grid_ClientProcess").append('<tr class="gradeA odd ItemRow" role="row"> <td style="text-align:center; vertical-align: middle;"> <input class="" type="checkbox" value="1" onchange="SelectProcess(this)"> </td><td> <input class="form-control" disabled="disabled" name="processName" type="text" value="' + value.name + '"> </td><td> <input class="form-control" disabled="disabled" name="processTotal" type="text"  value="' + value.totalPrice + '"> </td> <td hidden><input class="form-control" name="processes[' + index + ']" type="hidden" value="' + processId + '" disabled></td></tr>');
                index++;
            });
        }
    }

    function SelectProcess(CurrentCell) {
        if ($("#grid_ClientProcess").children().length > 0) {
            rowIndex = $(CurrentCell)
                    .closest('tr') // Get the closest tr parent element
                    .prevAll() // Find all sibling elements in front of it
                    .length; // Get their count
            SelectProcessByIndex(rowIndex);
            $("#ch_all").prop("checked", false);
            updateInvoicePrices();
            reArrangeProcess();
        }
    }

    function SelectProcessByIndex(rowIndex, flag = false) {
        var inputProcessId = $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0);
        var inputChk = $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0);
        if (!flag) {
            inputProcessId.prop("disabled", !inputProcessId.is(":disabled"));
            inputChk.prop("checked", !inputProcessId.is(":disabled"));
            flag = !inputProcessId.is(":disabled");
        } else {
            inputProcessId.prop("disabled", !flag);
            inputChk.prop("checked", flag);
        }
        if (flag) {
            //console.log('Load process items', inputProcessId.val(), allProcesses[currentClientId][inputProcessId.val()]['items']);
            addProcessPrices(currentClientId, inputProcessId.val());
            $.each(allProcesses[currentClientId][inputProcessId.val()]['items'], function (index, item) {
                invoiceItemsCount = $("#invoiceItems").children().length;
                var html = '<tr class="ItemRow processId_' + inputProcessId.val() + '">';
                html += '<td><div class="form-group"><input class="form-control total_price" name="invoiceItems[' + invoiceItemsCount + '][total_price]" value="' + (item.quantity * item.unit_price) + '" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control unit_price" name="invoiceItems[' + invoiceItemsCount + '][unit_price]" value="' + item.unit_price + '" placeholder="ادخل سعر الوحدة" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control quantity" name="invoiceItems[' + invoiceItemsCount + '][quantity]" value="' + item.quantity + '" placeholder="ادخل الكمية" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control size" name="invoiceItems[' + invoiceItemsCount + '][size]" value="" placeholder="ادخل المقاس" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control description" name="invoiceItems[' + invoiceItemsCount + '][description]" placeholder="ادخل تفاصيل البيان" value="' + item.description + '"/></div></td>';
                html += '<td><div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td>';
                html += '</tr>';
                $('#invoiceItems').append(html);
            });
        } else {
            removeProcessItems(inputProcessId.val());
            addProcessPrices(currentClientId, inputProcessId.val(), -1);
        }
        setProcessPricesText();
    }

    function reArrangeProcess() {
        //$('#invoiceItems tr:eq(' + rowIndex + ')').remove();
        $('#invoiceItems tr').each(function (rowIndex, row) {
            $('#invoiceItems tr:eq(' + rowIndex + ') .total_price').prop('name', 'invoiceItems[' + rowIndex + '][total_price]');
            $('#invoiceItems tr:eq(' + rowIndex + ') .unit_price').prop('name', 'invoiceItems[' + rowIndex + '][unit_price]');
            $('#invoiceItems tr:eq(' + rowIndex + ') .quantity').prop('name', 'invoiceItems[' + rowIndex + '][quantity]');
            $('#invoiceItems tr:eq(' + rowIndex + ') .size').prop('name', 'invoiceItems[' + rowIndex + '][size]');
            $('#invoiceItems tr:eq(' + rowIndex + ') .description').prop('name', 'invoiceItems[' + rowIndex + '][description]');
        });
    }

    function resetProcessPrices() {
        totalPrice = 0;
        discount = 0;
        taxes = 0;
        sourceDiscount = 0;
        totalPriceTaxes = 0;
        totalPaid = 0;
        totalRemaining = 0;
    }

    function addProcessPrices(clientId, processId, multiply = 1) {
        totalPrice += (parseFloat(allProcesses[clientId][processId].totalPrice) * multiply);
        discount += (parseFloat(allProcesses[clientId][processId].discount) * multiply);
        taxes += (parseFloat(allProcesses[clientId][processId].taxes) * multiply);
        sourceDiscount += (parseFloat(allProcesses[clientId][processId].sourceDiscount) * multiply);
        totalPriceTaxes += (parseFloat(allProcesses[clientId][processId].totalPriceTaxes) * multiply);
        totalPaid += (parseFloat(allProcesses[clientId][processId].totalPaid) * multiply);
        totalRemaining += (parseFloat(allProcesses[clientId][processId].totalRemaining) * multiply);
        if (totalPrice < 0)
            totalPrice = 0;
        if (discount < 0)
            discount = 0;
        if (taxes < 0)
            taxes = 0;
        if (sourceDiscount < 0)
            sourceDiscount = 0;
        if (totalPriceTaxes < 0)
            totalPriceTaxes = 0;
        if (totalPaid < 0)
            totalPaid = 0;
        if (totalRemaining < 0)
            totalRemaining = 0;
    }

    function setProcessPricesText() {
        $('.invoice_price').html(roundDecimals(totalPrice, 2));
        $('.discount_price').html(roundDecimals(discount, 2));
        $('.taxes_price').html(roundDecimals(taxes, 2));
        $('.source_discount_value').html(roundDecimals(sourceDiscount, 2));
        $('.total_price').html(roundDecimals(totalPriceTaxes, 2));
        $('.total_paid').html(roundDecimals(totalPaid, 2));
        $('.total_remaining').html(roundDecimals(totalRemaining, 2));

        $('input[name="invoice_price"]').val(roundDecimals(totalPrice, 2));
        $('input[name="discount_price"]').val(roundDecimals(discount, 2));
        $('input[name="taxes_price"]').val(roundDecimals(taxes, 2));
        $('input[name="source_discount_value"]').val(roundDecimals(sourceDiscount, 2));
        $('input[name="total_price"]').val(roundDecimals(totalPriceTaxes, 2));
        $('input[name="total_paid"]').val(roundDecimals(totalPaid, 2));
        $('input[name="total_remaining"]').val(roundDecimals(totalRemaining, 2));
    }

    function removeProcessItems(processId) {
        console.log('Remove process items');
        $(".processId_" + processId).remove();
    }

</script>
<script>
    $("#invoice_date").flatpickr({
        enableTime: false,
        maxDate: new Date(),
        @if (!isset($model)) defaultDate: new Date(), @endif
                altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar"
    });
</script>
@endsection
