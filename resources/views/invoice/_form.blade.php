
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
                        <button type="submit" class="btn btn-primary center-block" style="padding: 6px 30px;">عرض</button>
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>بيان</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>القيمة</th>
                                <th>تحكم</th>
                            </tr>
                        </thead>

                        <tbody id="prcoess_items">
                            @if($items)
                            @for ($i = 0; $i < count($items); $i++)
                            <tr class="{{ ($i != count($items) - 1) ? "skip" : "" }}" >
                                {{ Form::hidden("items[".$i."][id]") }}
                                <td>
                                    <div class="form-group{{ $errors->has("items.".$i.".description") ? " has-error" : "" }}">
                                        {{ Form::text("items[".$i."][description]", $items[$i]["description"], 
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
                                    <div class="form-group{{ $errors->has("items.".$i.".quantity") ? " has-error" : "" }}">
                                        {{ Form::text("items[".$i."][quantity]", $items[$i]["quantity"], 
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
                                    <div class="form-group{{ $errors->has("items.".$i.".unit_price") ? " has-error" : "" }}">
                                        {{ Form::text("items[".$i."][unit_price]", $items[$i]["unit_price"], 
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
                                    <div class="form-group{{ $errors->has("items.".$i.".total_price") ? " has-error" : "" }}">
                                        {{ Form::text("items[".$i."][total_price]", $items[$i]["total_price"], 
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
                                    <div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div>
                                </td>
                            </tr>
                            @endfor
                            @endif
                        </tbody>
                    </table>
                </div><!-- 
                <button id="add_process_items" class="btn btn-success"><i class="fa fa-plus-square"></i> أضف</button> -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>

@section('scripts')
<script>
    var allProcesses = JSON.parse(he.decode('{{ $clientProcesses }}'));
    console.log(allProcesses);
    var currentClientId = 0;
    function GetClientProcess(client_id) {
        currentClientId = $(client_id).val();
        FillterProcess();
    }

    function SelectAll(ch_all) {
        var rowsCount = $("#grid_ClientProcess").children().length;
        for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
            SelectProcessByIndex(rowIndex, $(ch_all).is(":checked"));
        }

    }

    function FillterProcess() {
        $("#grid_ClientProcess").empty();
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
        }
    }

    function SelectProcessByIndex(rowIndex, flag = false) {
        var inputProcessId = $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0);
        var inputChk = $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0);
        if(!flag) {
            flag = !inputProcessId.is(":disabled")
        }
        inputProcessId.prop("disabled", flag);
        inputChk.prop("checked", flag);
        var index = 0;
        if (flag) {
            //console.log('Load process items', inputProcessId.val());
            $.each(allProcesses[currentClientId][inputProcessId.val()]['items'], function (item) { 
                /*
                 *created_at,deleted_at,description,id,process_id,quantity,unit_price,updated_at
                 */
                index = $("#prcoess_items").children().length;
                $("#prcoess_items").append('<tr class="skip" >{{Form::hidden("items[' + index + '][id]")}}<td> <div class="form-group{{$errors->has("items.' + index + '.description") ? " has-error" : ""}}">{{Form::text("items[' + index + '][description]", "description", array( "class"=> "form-control", "placeholder"=> "ادخل تفاصيل البيان") )}}@if ($errors->has("items.' + index + '.description")) <label for="inputError" class="control-label">{{$errors->first("items.' + index + '.description")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("items.' + index + '.quantity") ? " has-error" : ""}}">{{Form::text("items[' + index + '][quantity]", "quantity", array( "class"=> "form-control quantity", "placeholder"=> "ادخل الكمية") )}}@if ($errors->has("items.' + index + '.quantity")) <label for="inputError" class="control-label">{{$errors->first("items.' + index + '.quantity")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("items.' + index + '.unit_price") ? " has-error" : ""}}">{{Form::text("items[' + index + '][unit_price]", "unit_price", array( "class"=> "form-control unit_price", "placeholder"=> "ادخل سعر الوحدة") )}}@if ($errors->has("items.' + index + '.unit_price")) <label for="inputError" class="control-label">{{$errors->first("items.' + index + '.unit_price")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("items.' + index + '.total_price") ? " has-error" : ""}}">{{Form::text("items[' + index + '][total_price]", "total_price", array( "class"=> "form-control total_price") )}}@if ($errors->has("items.' + index + '.total_price")) <label for="inputError" class="control-label">{{$errors->first("items.' + index + '.total_price")}}</label> @endif </div></td><td> <div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td></tr>');
            });
            /*
             <tr class="skip" ><input name="items[" + index + "][id]" type="hidden"><td> <div class="form-group"><input class="form-control" placeholder="ادخل تفاصيل البيان" name="items[" + index + "][description]" type="text" value="description"> </div></td><td> <div class="form-group"><input class="form-control quantity" placeholder="ادخل الكمية" name="items[" + index + "][quantity]" type="text" value="quantity"> </div></td><td> <div class="form-group"><input class="form-control unit_price" placeholder="ادخل سعر الوحدة" name="items[" + index + "][unit_price]" type="text" value="unit_price"> </div></td><td> <div class="form-group"><input class="form-control total_price" name="items[" + index + "][total_price]" type="text" value="total_price"> </div></td><td> <div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td></tr>
             */
        } else {
            //console.log('Remove process items');
        }
    }
</script>
@endsection