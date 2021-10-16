<?php use App\Models\ClientProcess; ?>

@extends("layouts.app")
@section("title", "تقرير عملية عميل - التقارير")
@section("content")
    <style>
        .pdfobject-container {
            height: 30rem;
            border: 1rem solid rgba(0, 0, 0, .1);
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">التقارير
                <small>تقرير عملية عميل</small>
            </h1>
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
                {{ Form::open(["route" => "reports.client.accountStatement.viewReport", 'method' => 'get']) }}
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
                        {{ Form::checkbox(null, "1", null,
                            array(
                                "id" => "ch_openprocess",
                                "class" => "checkbox_show_input",
                                "onchange" => "LoadClients()",
                                "checked" => "checked"
                            )
                        ) }}
                        {{ Form::label(null, "عمليات مفتوحة") }}
                        &ensp;
                        {{ Form::checkbox(null, "1", null,
                            array(
                                "id" => "ch_closedprocess",
                                "class" => "checkbox_show_input",
                                "onchange" => "LoadClients()"
                            )
                        ) }}
                        {{ Form::label(null, "عمليات مغلقة") }}
                    </div>
                    <div class="form-group">
                        {{ Form::label("client_id", "اسم العميل") }}
                        {{ Form::select("client_id", [], null,
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
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="depositwithdrawTable" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr role="row">
                                    <th rowspan="1" colspan="1" style="padding: 8px;">اختيار</th>
                                    <th rowspan="1" colspan="1">اسم العملية</th>
                                    <th rowspan="1" colspan="1">اجمالي</th>
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
                            <button type="submit" class="btn btn-primary center-block" style="padding: 6px 30px;">عرض
                            </button>
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
    <!-- /.row -->

@endsection

@section('scripts')
    <script>
        var allProcesses = {!! $clientProcesses !!};
        var clients = {!! $clients !!};
        console.log(clients);
        var currentClientId = 0;
        LoadClients();

        function LoadClients() {
            currentClientId = $(client_id).val();
            $("#client_id").empty();
            $("#grid_ClientProcess").empty();
            if (clients != null) {
                $("#client_id").append('<option></option>');
                $.each(clients, function (index, client) {
                    if (($("#ch_closedprocess").is(":checked") && client.hasClosedProcess == true) || ($("#ch_openprocess").is(":checked") && client.hasOpenProcess == true)) {
                        if (client.id == currentClientId) {
                            $("#client_id").append('<option selected value="' + client.id + '">' + client.name + '</option>');
                            FillterProcess();
                        } else {
                            $("#client_id").append('<option value="' + client.id + '">' + client.name + '</option>');
                        }
                    }
                });
            }
        }

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
                    if (($("#ch_closedprocess").is(":checked") && value.status == '{{ ClientProcess::statusClosed }}') || ($("#ch_openprocess").is(":checked") && value.status == '{{ ClientProcess::statusOpened }}')) {
                        $("#grid_ClientProcess").append('<tr class="gradeA odd ItemRow" role="row"> <td style="text-align:center; vertical-align: middle;"> <input class="" type="checkbox" value="1" onchange="SelectProcess(this)"> </td><td> <input class="form-control" disabled="disabled" name="processName" type="text" value="' + value.name + '"> </td><td> <input class="form-control" disabled="disabled" name="processTotal" type="text"  value="' + value.totalPrice + '"> </td> <td hidden><input class="form-control" disabled name="processes[' + index + ']" type="hidden" value="' + processId + '"></td></tr>');
                    }
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
            if (!flag) {
                inputProcessId.prop("disabled", !inputProcessId.is(":disabled"));
                inputChk.prop("checked", !inputProcessId.is(":disabled"));
            } else {
                inputProcessId.prop("disabled", !flag);
                inputChk.prop("checked", flag);
            }
        }

    </script>
@endsection