@extends("layouts.app") 
@section("title", "تقرير عملية عميل - التقارير")
@section("content")

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">التقارير <small>تقرير عملية عميل</small></h1>
    </div>
</div>
<!-- /.row -->

<div class="row">

    @if (session("success"))
    <div class="alert alert-success">
        {{ session("success") }}
    </div>
    @endif

    @if (session("error"))
    <div class="alert alert-danger">
        {{ session("error") }}
    </div>
    @endif

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                تقرير عملية عميل
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(["url" => "reports/client/viewClientReport", 'id' => 'depositwithdrawForm', "method" => "get"]) }}
            <div class="panel-body">

                <div class="legend">

                    {{ Form::radio("ch_detialed", "1", null, 
                        array(
                            "id" => "ch_detialed",
                            "class" => "checkbox_show_input",
                            "required" => "required"
                        )
                    ) }} 
                    {{ Form::label("ch_detialed", "مفصل") }}
                    &ensp;
                    {{ Form::radio("ch_detialed", "0", null, 
                        array(
                            "id" => "ch_total",
                            "class" => "checkbox_show_input",
                            "required" => "required"
                        )
                    ) }} 
                    {{ Form::label("ch_total", "مجمع") }}
                </div>
                <div class="legend">
                    {{ Form::checkbox("ch_openprocess", "1", null, 
                        array(
                            "id" => "ch_openprocess",
                            "class" => "checkbox_show_input",
                            "onchange" => "FillterProcess()"
                        )
                    ) }} 
                    {{ Form::label("ch_openprocess", "عمليات مفتوحة") }}
                    &ensp;
                    {{ Form::checkbox("ch_closedprocess", "1", null, 
                        array(
                            "id" => "ch_closedprocess",
                            "class" => "checkbox_show_input",
                            "onchange" => "FillterProcess()"
                        )
                    ) }} 
                    {{ Form::label("ch_closedprocess", "عمليات مغلقة") }}
                </div>
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
                    {{ Form::checkbox("ch_all", "1", null, 
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
                        <button type="submit" class="btn btn-primary center-block" onclick="LockSaveToAll()" style="padding: 6px 30px;">عرض</button>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            {{ Form::close() }}
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>


<script>
    var clientProcess;
    $("#ch_openprocess").prop("checked", true);

    function GetClientProcess(client_id) {
        client_id = $(client_id).val();
        //console.log(client_id);
        $.get('{{ url("reports/client/getClientProcesses/") }}', {option: client_id},
                function (data) {
                    clientProcess = data;
                    FillterProcess();
                });
    }

    function SelectAll(ch_all) {
        var rowsCount = $("#grid_ClientProcess").children().length;
        if ($(ch_all).is(":checked")) {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", true);
                ;
            }
        } else {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_ClientProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", false);
                ;
            }
        }
    }

    function FillterProcess() {
        $("#grid_ClientProcess").empty();
        var index = 0;
        $.each(clientProcess, function (key, value) {
            
            if ($("#ch_openprocess").is(":checked") && value.status == "active") {
                $("#grid_ClientProcess").append('<tr class="gradeA odd ItemRow" role="row"> <td style="text-align:center; vertical-align: middle;"> <input class="" id="processChoice" name="processChoice" type="checkbox" value="1"> </td><td> <input class="form-control" id="processName"  name="processName" type="text" value="' + value.name + '"> </td><td> <input class="form-control" id="processTotal" disabled="disabled" name="processTotal" type="text"  value="' + value.totalPrice + '"> </td> <td hidden><input class="form-control" id="processName"  name="processes[' + index + '][id]" type="text" value="' + key + '"></td></tr>');
            }
            if ($("#ch_closedprocess").is(":checked") && value.status == "closed") {
                $("#grid_ClientProcess").append('<tr class="gradeA odd ItemRow" role="row"> <td style="text-align:center; vertical-align: middle;"> <input class="" id="processChoice" name="processChoice" type="checkbox" value="1"> </td><td> <input class="form-control" id="processName"  name="processName" type="text" value="' + value.name + '"> </td><td> <input class="form-control" id="processTotal" disabled="disabled" name="processTotal" type="text"  value="' + value.totalPrice + '"> </td> <td hidden><input class="form-control" id="processName"  name="processes[' + index + '][id]" type="text" value="' + key + '"></td></tr>');
            }
            index++;
        });
    }

</script>
@endsection