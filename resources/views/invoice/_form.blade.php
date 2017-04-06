
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
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>
                            <span>مبلغ الفاتورة </span>
                            <span class="price invoice_priceI">0</span>
                        </h4>
                        <h4>
                            <span>الخصم </span>
                            <span class="price discount_priceI">0</span>
                        </h4>
                        <h4>
                            <span>الضريبة المضافة </span>
                            <span class="price taxes_priceI">0</span>
                        </h4>
                        <h4>
                            <span>خصم من المنبع </span>
                            <span class="price source_discount_valueI">0</span>
                        </h4>
                        <hr>
                        <h4>
                            <span>القيمة اﻻجمالية </span>
                            <span class="price final_priceI">0</span>
                        </h4>
                    </div>
                </div>

                {{ Form::hidden('invoice_priceI') }}
                {{ Form::hidden('discount_priceI') }}
                {{ Form::hidden('taxes_priceI') }}
                {{ Form::hidden('source_discount_valueI') }}
                {{ Form::hidden('final_priceI') }}
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::checkbox("withLetterHead", "1", 1, 
                            array(
                                "id" => "withLetterHead",
                                "class" => "checkbox_show_input"
                            )
                        ) }} 
                        {{ Form::label("withLetterHead", "طباعة الليتر هد") }}
                        <br>
                        <button type="button" class="btn btn-block btn-primary center-block" onclick="submitForm()">معاينة طباعة</button>
                        <br>
                        <button type="button" class="btn btn-block btn-primary center-block">اصدار</button>
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
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>القيمة</th>
                                        <th>سعر الوحدة</th>
                                        <th>الكمية</th>
                                        <th>المقاس</th>
                                        <th>بيان</th>
                                        <th>تحكم</th>
                                    </tr>
                                </thead>

                                <tbody id="invoiceItems">
                                    @if($invoiceItems)
                                    @for ($i = 0; $i < count($invoiceItems); $i++)
                                    <tr class="{{ ($i != count($invoiceItems) - 1) ? "skip" : "" }}" >
                                        {{ Form::hidden("invoiceItems[".$i."][id]") }}
                                        <td>
                                            <div class="form-group{{ $errors->has("items.".$i.".description") ? " has-error" : "" }}">
                                                {{ Form::text("invoiceItems[".$i."][description]", $invoiceItems[$i]["description"], 
                                            array(
                                                "class" => "form-control", 
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
                                                "class" => "form-control quantity", 
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
                            <span class="price final_price">0</span>
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
    var discount = 0;
    var sourceDiscount = 0;
    var taxes = 0;
    
    function submitForm() {
        $('#invoiceForm').prop('action', 'preview').submit();
    }
    
    function GetClientProcess(client_id) {
        currentClientId = $(client_id).val();
        FillterProcess();
    }

    function SelectAll(ch_all) {
        var rowsCount = $("#grid_ClientProcess").children().length;
        resetProcessPrices();
        for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
            SelectProcessByIndex(rowIndex, $(ch_all).is(":checked"));
        }
        update_prices();
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
        }
    }

    function SelectProcessByIndex(rowIndex, flag = false) {
        var inputProcessId = $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0);
        var inputChk = $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0);
        if (!flag) {
            flag = !inputProcessId.is(":disabled")
        }
        inputProcessId.prop("disabled", flag);
        inputChk.prop("checked", flag);
        if (flag) {
            //console.log('Load process items', inputProcessId.val(), allProcesses[currentClientId][inputProcessId.val()]['items']);
            addProcessPrices(currentClientId, inputProcessId.val());
            $.each(allProcesses[currentClientId][inputProcessId.val()]['items'], function (index, item) {
                processItemsCount = $("#invoiceItems").children().length + 1;
                var html = '<tr class="processId_' + inputProcessId.val() + '">';
                html += '<td><div class="form-group"><input class="form-control total_price" name="invoiceItems[' + processItemsCount + '][total_price]" value="' + (item.quantity * item.unit_price) + '" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control unit_price" name="invoiceItems[' + processItemsCount + '][unit_price]" value="' + item.unit_price + '" placeholder="ادخل سعر الوحدة" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control quantity" name="invoiceItems[' + processItemsCount + '][quantity]" value="' + item.quantity + '" placeholder="ادخل الكمية" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control quantity" name="invoiceItems[' + processItemsCount + '][size]" value="" placeholder="ادخل المقاس" /></div></td>';
                html += '<td><div class="form-group"><input class="form-control" name="invoiceItems[' + processItemsCount + '][description]" placeholder="ادخل تفاصيل البيان" value="' + item.description + '"/></div></td>';
                html += '<td><div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td>';
                html += '</tr>';
                $('#invoiceItems').append(html);
            });
        } else {
            removeProcessItems(currentClientId, inputProcessId.val());
            addProcessPrices(currentClientId, inputProcessId.val(), -1);
        }
        setProcessPricesText();
    }
    function resetProcessPrices() {
        totalPrice = 0;
        discount = 0;
        taxes = 0;
        sourceDiscount = 0;
        totalPriceTaxes = 0;
    }

    function addProcessPrices(clientId, processId, multiply = 1) {
        totalPrice += (parseFloat(allProcesses[clientId][processId].totalPrice) * multiply);
        discount += (parseFloat(allProcesses[clientId][processId].discount) * multiply);
        taxes += (parseFloat(allProcesses[clientId][processId].taxes) * multiply);
        sourceDiscount += (parseFloat(allProcesses[clientId][processId].sourceDiscount) * multiply);
        totalPriceTaxes += (parseFloat(allProcesses[clientId][processId].totalPriceTaxes) * multiply);
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
    }

    function setProcessPricesText() {
        $('.invoice_priceI').html(roundDecimals(totalPrice, 2));
        $('.discount_priceI').html(roundDecimals(discount, 2));
        $('.taxes_priceI').html(roundDecimals(taxes, 2));
        $('.source_discount_valueI').html(roundDecimals(sourceDiscount, 2));
        $('.final_priceI').html(roundDecimals(totalPriceTaxes, 2));
        
        $('input[name="invoice_priceI"]').val(roundDecimals(totalPrice, 2));
        $('input[name="discount_priceI"]').val(roundDecimals(discount, 2));
        $('input[name="taxes_priceI"]').val(roundDecimals(taxes, 2));
        $('input[name="source_discount_valueI"]').val(roundDecimals(sourceDiscount, 2));
        $('input[name="final_priceI"]').val(roundDecimals(totalPriceTaxes, 2));
    }

    function removeProcessItems(clientId, processId) {
        console.log('Remove process items');
        $(".processId_" + processId).remove();
    }
</script>
@endsection
