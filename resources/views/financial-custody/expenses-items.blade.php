@extends('layouts.app')

@section('title', 'مصروفات العهدة')

@section('content')
    <style>
        @media (min-width: 320px) and (max-width: 1440px) {
            #dataTables-example_wrapper .col-sm-12 form {
                overflow: auto;
            }

            .index .dataTables_wrapper .row {
                margin-bottom: 10px;
            }
        }

        @media (min-width: 320px) and (max-width: 1295px) {
            #dataTables-example_wrapper .col-sm-12 form {
                overflow: auto;
            }

            .index .dataTables_wrapper .row {
                margin-bottom: 10px;
            }
        }

        tr.InSave {

        }

        tr.InSave td {
            background-color: red !important;
        }

    </style>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">مصروفات العهدة / {{ $employee_name }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    كشف حساب العهد
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">

                        <div class="table-responsive">
                            <table width="2000" class="table table-striped table-bordered table-hover"
                                   id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>م</th>
                                    <th>عهدة</th>
                                    <th>بيان</th>
                                    <th>التاريخ</th>
                                    <th>ملاحظات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($financialCustodyDeposits as $index => $financialCustodyDeposit)
                                    <tr class="odd">
                                        <td hidden>{{ $financialCustodyDeposit->id }}</td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $financialCustodyDeposit->withdrawValue }}</td>
                                        <td>{{ $financialCustodyDeposit->recordDesc }}</td>
                                        <td>{{ $financialCustodyDeposit->due_date }}</td>
                                        <td>{{ $financialCustodyDeposit->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>ﻻ يوجد بيانات.</tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    مصروفات العهدة
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="row text-center">
                        <div class="col-sm-4">
                            <div class="alert alert-info">
                                <label>اجمالي العهدة : <span id="depositsAmount">{{ $amounts['depositsAmount'] }}</span>
                                    جنيه</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="alert alert-danger">
                                <label>المنصرف : <span id="withdrawsAmount">{{ $amounts['withdrawsAmount'] }}</span>
                                    جنيه</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="alert alert-success">
                                <label>الرصيد الحالي : <span
                                            id="currentAmount">{{ $amounts['currentAmount'] }}</span>
                                    جنيه</label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="dataTable_wrapper">
                            <div id="dataTables-example_wrapper"
                                 class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                                <div class="row">
                                    <div class="col-sm-12">
                                        {{ Form::open(['route' => 'depositwithdraw.store', 'id' => 'depositwithdrawForm', "role" => "form"]) }}
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                               id="depositwithdrawTable" role="grid"
                                               aria-describedby="dataTables-example_info">
                                            <thead>
                                            <tr role="row">
                                                <th rowspan="1" colspan="1" style="width:20px;">اختيار</th>
                                                <th rowspan="1" colspan="1" hidden>وارد</th>
                                                <th rowspan="1" colspan="1">منصرف</th>
                                                <th rowspan="1" colspan="1">بيان</th>
                                                <th rowspan="1" colspan="1">اسم العملية</th>
                                                <th rowspan="1" colspan="1">اسم العميل</th>
                                                <th rowspan="1" colspan="1">اسم المورد</th>
                                                <th rowspan="1" colspan="1" hidden>اسم الموظف</th>
                                                <th rowspan="1" colspan="1" hidden>اسم المصروف</th>
                                                <th rowspan="1" colspan="1">ملاحظات</th>
                                            </tr>
                                            </thead>
                                            <tbody id="grid_FinancialCustodyDetails">
                                            @forelse ($financialCustodyItems as $financialCustodyItem)
                                                <tr class="gradeA odd ItemRow Saved" role="row">
                                                    <td>
                                                        <input type="checkbox" value="" class="checkDelete">
                                                    </td>
                                                    <td hidden>
                                                        <div class="form-group{{ $errors->has("depositValue") ? " has-error" : "" }}">
                                                            {{ Form::text("depositValue", ($financialCustodyItem->depositValue > 0 ? $financialCustodyItem->depositValue : null),
                                                                        array(
                                                                            "class" => "form-control IsNumberDecimal depositValue",
                                                                            "id" => "",
                                                                            "style" => "width:85px;",
                                                                            "onchange" => "AddNewRow(this)",
                                                                            "onblur" => "OnRowLeave(this)",
                                                                            "onfocus" => "OnRowFocus(this)")
                                                                        )
                                                            }}
                                                            @if ($errors->has("depositValue"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("depositValue") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group{{ $errors->has("withdrawValue") ? " has-error" : "" }}">
                                                            {{ Form::text("withdrawValue", ($financialCustodyItem->withdrawValue > 0 ? $financialCustodyItem->withdrawValue : null),
                                                                        array(
                                                                            "class" => "form-control IsNumberDecimal withdrawValue",
                                                                            "id" => "",
                                                                            "style" => "width:85px;",
                                                                            "onchange" => "AddNewRow(this)",
                                                                            "onblur" => "OnRowLeave(this)",
                                                                            "onfocus" => "OnRowFocus(this)")
                                                                        )
                                                            }}
                                                            @if ($errors->has("withdrawValue"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("withdrawValue") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group{{ $errors->has("recordDesc") ? " has-error" : "" }}">
                                                            {{ Form::text("recordDesc", $financialCustodyItem->recordDesc,
                                                                        array(
                                                                            "class" => "form-control recordDesc",
                                                                            "id" => "",
                                                                            "style" => "width:85px;",
                                                                            "onchange" => "AddNewRow(this)",
                                                                            "onblur" => "OnRowLeave(this)",
                                                                            "onfocus" => "OnRowFocus(this)")
                                                                        )
                                                            }}
                                                            @if ($errors->has("recordDesc"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("recordDesc") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group{{ $errors->has("cbo_processes") ? " has-error" : "" }}">
                                                            {{ Form::select("cbo_processes", [$financialCustodyItem->cbo_processes => $financialCustodyItem->cbo_processes], $financialCustodyItem->cbo_processes,
                                                                        array(
                                                                            "class" => "form-control cbo_processes",
                                                                            "placeholder" => "",
                                                                            "id" => "",
                                                                            "style" => "width:85px;",
                                                                            "onchange" => "AddNewRow(this)",
                                                                            "onblur" => "OnRowLeave(this)",
                                                                            "onfocus" => "OnRowFocus(this)")
                                                                            )
                                                            }}
                                                            @if ($errors->has("cbo_processes"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("cbo_processes") }}
                                                                </label>
                                                            @endif
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="form-group{{ $errors->has("client_id") ? " has-error" : "" }}">
                                                            {{ Form::select("client_id", [$financialCustodyItem->client_id => $financialCustodyItem->client_id], $financialCustodyItem->client_id,
                                                                array(
                                                                    "class" => "form-control client_id",
                                                                    "placeholder" => "",
                                                                    "id" => "",
                                                                    "style" => "width:85px;",
                                                                    "onchange" => "AddNewRow(this)",
                                                                    "onblur" => "OnRowLeave(this)",
                                                                    "onfocus" => "OnRowFocus(this)")
                                                                )
                                                            }}
                                                            @if ($errors->has("client_id"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("client_id") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group{{ $errors->has("supplier_id") ? " has-error" : "" }}">
                                                            {{ Form::select("supplier_id", [$financialCustodyItem->supplier_id => $financialCustodyItem->supplier_id], $financialCustodyItem->supplier_id,
                                                                array(
                                                                    "class" => "form-control supplier_id",
                                                                    "placeholder" => "",
                                                                    "id" => "",
                                                                    "style" => "width:85px;",
                                                                    "onchange" => "AddNewRow(this)",
                                                                    "onblur" => "OnRowLeave(this)",
                                                                    "onfocus" => "OnRowFocus(this)")
                                                                )
                                                            }}
                                                            @if ($errors->has("supplier_id"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("supplier_id") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td hidden>
                                                        <div class="form-group{{ $errors->has("employee_id") ? " has-error" : "" }}">
                                                            {{ Form::select("employee_id", $employees, $financialCustodyItem->employee_id,
                                                                array(
                                                                    "class" => "form-control employee_id",
                                                                    "placeholder" => "",
                                                                    "id" => "",
                                                                    "style" => "width:85px;",
                                                                    "onchange" => "AddNewRow(this)",
                                                                    "onblur" => "OnRowLeave(this)",
                                                                    "onfocus" => "OnRowFocus(this)")
                                                                )
                                                            }}
                                                            @if ($errors->has("employee_id"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("employee_id") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td hidden>
                                                        <div class="form-group{{ $errors->has("expenses_id") ? " has-error" : "" }}">
                                                            {{ Form::select("expenses_id", [$financialCustodyItem->expenses_id => $financialCustodyItem->expenses_id], $financialCustodyItem->expenses_id,
                                                                array(
                                                                    "class" => "form-control expenses_id",
                                                                    "placeholder" => "",
                                                                    "id" => "",
                                                                    "style" => "width:85px;",
                                                                    "onchange" => "AddNewRow(this)",
                                                                    "onblur" => "OnRowLeave(this)",
                                                                    "onfocus" => "OnRowFocus(this)")
                                                                )
                                                            }}
                                                            @if ($errors->has("expenses_id"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("expenses_id") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group{{ $errors->has("notes") ? " has-error" : "" }}">
                                                            {{ Form::text("notes", $financialCustodyItem->notes,
                                                                        array(
                                                                            "class" => "form-control notes",
                                                                            "id" => "",
                                                                            "style" => "width:85px;",
                                                                            "onchange" => "AddNewRow(this)",
                                                                            "onblur" => "OnRowLeave(this)",
                                                                            "onfocus" => "OnRowFocus(this)")
                                                                        )
                                                            }}
                                                            @if ($errors->has("notes"))
                                                                <label for="inputError" class="control-label">
                                                                    {{ $errors->first("notes") }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td hidden>
                                                        <input type="hidden" class="id" onchange="AddNewRow(this)"
                                                               onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)"
                                                               value="{{ $financialCustodyItem->id }}">
                                                    </td>
                                                    <td hidden>
                                                        <input type="hidden" class="saveStatus"
                                                               onchange="AddNewRow(this)"
                                                               onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)"
                                                               value="{{ $financialCustodyItem->saveStatus }}">
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                            <tr class="gradeA odd ItemRow" role="row">
                                                <td>
                                                    <input type="checkbox" value="" class="checkDelete">
                                                </td>
                                                <td hidden>
                                                    <div class="form-group{{ $errors->has("depositValue") ? " has-error" : "" }}">
                                                        {{ Form::text("depositValue", null,
                                                                    array(
                                                                        "class" => "form-control IsNumberDecimal depositValue",
                                                                        "id" => "",
                                                                        "style" => "width:85px;",
                                                                        "onchange" => "AddNewRow(this)",
                                                                        "onblur" => "OnRowLeave(this)",
                                                                        "onfocus" => "OnRowFocus(this)")
                                                                    )
                                                        }}
                                                        @if ($errors->has("depositValue"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("depositValue") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group{{ $errors->has("withdrawValue") ? " has-error" : "" }}">
                                                        {{ Form::text("withdrawValue", null,
                                                                    array(
                                                                        "class" => "form-control IsNumberDecimal withdrawValue",
                                                                        "id" => "",
                                                                        "style" => "width:85px;",
                                                                        "onchange" => "AddNewRow(this)",
                                                                        "onblur" => "OnRowLeave(this)",
                                                                        "onfocus" => "OnRowFocus(this)")
                                                                    )
                                                        }}
                                                        @if ($errors->has("withdrawValue"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("withdrawValue") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group{{ $errors->has("recordDesc") ? " has-error" : "" }}">
                                                        {{ Form::text("recordDesc", null,
                                                                    array(
                                                                        "class" => "form-control recordDesc",
                                                                        "id" => "",
                                                                        "style" => "width:85px;",
                                                                        "onchange" => "AddNewRow(this)",
                                                                        "onblur" => "OnRowLeave(this)",
                                                                        "onfocus" => "OnRowFocus(this)")
                                                                    )
                                                        }}
                                                        @if ($errors->has("recordDesc"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("recordDesc") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group{{ $errors->has("cbo_processes") ? " has-error" : "" }}">
                                                        {{ Form::select("cbo_processes", [], null,
                                                                    array(
                                                                        "class" => "form-control cbo_processes",
                                                                        "placeholder" => "",
                                                                        "id" => "",
                                                                        "style" => "width:85px;",
                                                                        "onchange" => "AddNewRow(this)",
                                                                        "onblur" => "OnRowLeave(this)",
                                                                        "onfocus" => "OnRowFocus(this)")
                                                                        )
                                                        }}
                                                        @if ($errors->has("cbo_processes"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("cbo_processes") }}
                                                            </label>
                                                        @endif
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="form-group{{ $errors->has("client_id") ? " has-error" : "" }}">
                                                        {{ Form::select("client_id", [], null,
                                                            array(
                                                                "class" => "form-control client_id",
                                                                "placeholder" => "",
                                                                "id" => "client_id",
                                                                "style" => "width:85px;",
                                                                "onchange" => "AddNewRow(this)",
                                                                "onblur" => "OnRowLeave(this)",
                                                                "onfocus" => "OnRowFocus(this)")
                                                            )
                                                        }}
                                                        @if ($errors->has("client_id"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("client_id") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group{{ $errors->has("supplier_id") ? " has-error" : "" }}">
                                                        {{ Form::select("supplier_id", [], null,
                                                            array(
                                                                "class" => "form-control supplier_id",
                                                                "placeholder" => "",
                                                                "id" => "",
                                                                "style" => "width:85px;",
                                                                "onchange" => "AddNewRow(this)",
                                                                "onblur" => "OnRowLeave(this)",
                                                                "onfocus" => "OnRowFocus(this)")
                                                            )
                                                        }}
                                                        @if ($errors->has("supplier_id"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("supplier_id") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="form-group{{ $errors->has("employee_id") ? " has-error" : "" }}">
                                                        {{ Form::select("employee_id", $employees, null,
                                                            array(
                                                                "class" => "form-control employee_id",
                                                                "placeholder" => "",
                                                                "id" => "",
                                                                "style" => "width:85px;",
                                                                "onchange" => "AddNewRow(this)",
                                                                "onblur" => "OnRowLeave(this)",
                                                                "onfocus" => "OnRowFocus(this)")
                                                            )
                                                        }}
                                                        @if ($errors->has("employee_id"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("employee_id") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="form-group{{ $errors->has("expenses_id") ? " has-error" : "" }}">
                                                        {{ Form::select("expenses_id", [], null,
                                                            array(
                                                                "class" => "form-control expenses_id",
                                                                "placeholder" => "",
                                                                "id" => "",
                                                                "style" => "width:85px;",
                                                                "onchange" => "AddNewRow(this)",
                                                                "onblur" => "OnRowLeave(this)",
                                                                "onfocus" => "OnRowFocus(this)")
                                                            )
                                                        }}
                                                        @if ($errors->has("expenses_id"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("expenses_id") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group{{ $errors->has("notes") ? " has-error" : "" }}">
                                                        {{ Form::text("notes", null,
                                                                    array(
                                                                        "class" => "form-control notes",
                                                                        "id" => "",
                                                                        "style" => "width:85px;",
                                                                        "onchange" => "AddNewRow(this)",
                                                                        "onblur" => "OnRowLeave(this)",
                                                                        "onfocus" => "OnRowFocus(this)")
                                                                    )
                                                        }}
                                                        @if ($errors->has("notes"))
                                                            <label for="inputError" class="control-label">
                                                                {{ $errors->first("notes") }}
                                                            </label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <input type="hidden" class="id" onchange="AddNewRow(this)"
                                                           onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)"
                                                           value="-1">
                                                </td>
                                                <td hidden>
                                                    <input type="hidden" class="saveStatus" onchange="AddNewRow(this)"
                                                           onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)"
                                                           value="0">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="canEdit" name="canEdit" value="{{ $canEdit }}"/>
                                        {{ Form::close() }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger" onclick="removeItems()">حذف</button>
                            @if(Entrust::ability('admin', 'manage-financial-custody'))
                                <button type="button" class="btn btn-primary" onclick="acceptItems()">قبول</button>
                                <button type="button" class="btn btn-primary" onclick="">تسوية العهدة</button>
                            @endif
                        </div>

                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
@endsection

@section('scripts')
    <script>
        var expenses = {!! $expenses !!};
        var employeeActions = {!! $employeeActions !!};
        var suppliers = {!! $suppliers !!};
        var clients = {!! $clients !!};
        var checkDelete, depositValue, withdrawValue, cbo_processes, client_id, supplier_id, employee_id, expenses_id,
            recordDesc, notes, saveStatus, id, flag, canEdit, currentAmount, withdrawsAmount, depositsAmount;
        var CurrentCell, CurrentCellName, CurrentRow, AfterCurrentRow, currentRowIndex, lastRowIndex = -1, rowCount = 1;
        var loadAll = false;

        console.log('expenses: ', expenses);
        console.log('employeeActions: ', employeeActions);

        LockAll();
        SetFinancialCustodyDetailsProcess();
        currentAmount = $("#currentAmount");
        withdrawsAmount = $("#withdrawsAmount");
        depositsAmount = $("#depositsAmount");

        function loadAllProcessAndClients() {
            loadAll = $("#loadAllProcessAndClients").prop("checked");
            SetFinancialCustodyDetailsProcess();
        }

        function LoadProcess(rowIndex) {
            client_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .client_id');
            supplier_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .supplier_id');
            cbo_processes = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .cbo_processes');
            employee_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .employee_id');
            expenses_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .expenses_id');
            cbo_processesVal = cbo_processes.val();
            expenses_idVal = expenses_id.val();
            client_idVal = client_id.val();
            supplier_idVal = supplier_id.val();
            cbo_processes.empty();
            cbo_processes.append($("<option></option>"));
            expenses_id.empty();
            expenses_id.append($("<option></option>"));
            client_id.empty();
            client_id.append($("<option></option>"));
            supplier_id.empty();
            supplier_id.append($("<option></option>"));
            if (client_idVal > 0) {
                $.each(clients, function (clientId, client) {
                    if (clientId === client_idVal) {
                        client_id.append($("<option selected></option>").attr("value", clientId).text(client.name));
                    } else {
                        if (client.hasOpenProcess || loadAll) {
                            client_id.append($("<option></option>").attr("value", clientId).text(client.name));
                        }
                    }
                });
                $.each(clients[client_idVal].processes, function (processId, process) {
                    if (processId === cbo_processesVal) {
                        cbo_processes.append($("<option selected></option>").attr("value", processId).text(process.name));
                    } else {
                        if (process.status === 'active' || loadAll) {
                            cbo_processes.append($("<option></option>").attr("value", processId).text(process.name));
                        }
                    }
                });
            } else if (supplier_idVal > 0) {
                $.each(suppliers, function (supplierId, supplier) {
                    if (supplierId === supplier_idVal) {
                        supplier_id.append($("<option selected></option>").attr("value", supplierId).text(supplier.name));
                    } else {
                        if (supplier.hasOpenProcess || loadAll) {
                            supplier_id.append($("<option></option>").attr("value", supplierId).text(supplier.name));
                        }
                    }
                });
                $.each(suppliers[supplier_idVal].processes, function (processId, process) {
                    if (processId === cbo_processesVal) {
                        cbo_processes.append($("<option selected></option>").attr("value", processId).text(process.name));
                    } else {
                        if (process.status === 'active' || loadAll) {
                            cbo_processes.append($("<option></option>").attr("value", processId).text(process.name));
                        }
                    }
                });
            }
            if (employee_id.val() > 0) {
                $.each(employeeActions, function (i, employeeAction) {
                    console.log(employeeAction.id, expenses_idVal, (employeeAction.id === expenses_idVal));
                    if (employeeAction.id == expenses_idVal) {
                        expenses_id.append('<option selected value="' + employeeAction.id + '">' + employeeAction.name + '</option>');
                    } else {
                        expenses_id.append('<option value="' + employeeAction.id + '">' + employeeAction.name + '</option>');
                    }
                });
            } else if (expenses_idVal > 0) {
                $.each(expenses, function (i, expense) {
                    if (expense.id == expenses_idVal) {
                        expenses_id.append('<option selected value="' + expense.id + '">' + expense.name + '</option>');
                    } else {
                        expenses_id.append('<option value="' + expense.id + '">' + expense.name + '</option>');
                    }
                });
            }
        }

        function SetFinancialCustodyDetailsProcess() {
            var rowsCount = $('#grid_FinancialCustodyDetails').children().length;
            for (var rowIndex = 0; rowIndex < rowsCount - 1; rowIndex++) {
                LoadProcess(rowIndex);
            }
        }

        function OnRowFocus(CellChildInput) {
            SetCurrentRowIndex(CellChildInput);
            if (currentRowIndex !== lastRowIndex && lastRowIndex > -1) {
                // check valdiation
                if (IsValid(lastRowIndex)) {
                    SaveCurrentRow();
                }
                lastRowIndex = -1;
            } else {
                SetLastRowIndex();
            }
        }

        function OnRowLeave(CellChildInput) {
            SetCurrentRowIndex(CellChildInput);
            if (currentRowIndex >= rowCount - 1) {
                lastRowIndex = -1;
            } else {
                SetLastRowIndex();
            }
        }

        function IsValid(rowIndex) {
            if (rowIndex === -1) {
                return false;
            }
            checkDelete = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .checkDelete');
            depositValue = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .depositValue');
            withdrawValue = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .withdrawValue');
            recordDesc = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .recordDesc');
            cbo_processes = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .cbo_processes');
            client_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .client_id');
            supplier_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .supplier_id');
            employee_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .employee_id');
            expenses_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .expenses_id');
            notes = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .notes');
            id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .id');
            saveStatus = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .saveStatus');
            var flag = true;
            if (recordDesc.val() === '') {
                recordDesc.parent().addClass("has-error");
                flag = false;
            } else {
                recordDesc.parent().removeClass("has-error");
            }

            if (depositValue.val() === '' && withdrawValue.val() === '') {
                depositValue.parent().addClass("has-error");
                withdrawValue.parent().addClass("has-error");
                flag = false;
            } else {
                depositValue.parent().removeClass("has-error");
                withdrawValue.parent().removeClass("has-error");
            }
            if (supplier_id.val() === '' && client_id.val() === '' && employee_id.val() === '' && expenses_id.val() === '') {
                cbo_processes.parent().addClass("has-error");
                client_id.parent().addClass("has-error");
                supplier_id.parent().addClass("has-error");
                employee_id.parent().addClass("has-error");
                expenses_id.parent().addClass("has-error");
                flag = false;
            } else {
                cbo_processes.parent().removeClass("has-error");
                client_id.parent().removeClass("has-error");
                supplier_id.parent().removeClass("has-error");
                employee_id.parent().removeClass("has-error");
                expenses_id.parent().removeClass("has-error");
            }
            if (expenses_id.val() === '' && cbo_processes.val() === '') {
                cbo_processes.parent().addClass("has-error");
                expenses_id.parent().addClass("has-error");
                flag = false;
            } else {
                cbo_processes.parent().removeClass("has-error");
                expenses_id.parent().removeClass("has-error");
            }
            return flag;
        }

        function SaveCurrentRow() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            canEdit = $("#canEdit").val();
            var due_date = '{{ $amounts["current_dayOfMonth"] }}-{{ $amounts["current_month"] + 1 }}-{{ $amounts["current_year"] }}';
            var formData = {
                checkDelete: checkDelete.val(),
                depositValue: depositValue.val(),
                withdrawValue: withdrawValue.val(),
                due_date: due_date,
                recordDesc: recordDesc.val(),
                cbo_processes: cbo_processes.val(),
                client_id: client_id.val(),
                supplier_id: supplier_id.val(),
                employee_id: employee_id.val(),
                expenses_id: expenses_id.val(),
                notes: notes.val(),
                saveStatus: saveStatus.val()
            };
            var URL = '{{ url("/financialCustodyItems") }}';
            var METHOD = 'POST';
            if (saveStatus.val() === '1' || (saveStatus.val() === '2' && canEdit === '1')) {
                URL += '/' + id.val();
                METHOD = 'PUT';
            }
            URL += '?employee_id={{$employee_id}}';
            checkDelete.parent().parent().addClass('InSave');
            $.ajax({
                type: METHOD,
                url: URL,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        saveStatus.val(1);
                        checkDelete.parent().parent().removeClass('InSave');
                        id.val(data.id);
                        currentAmount.html(data.currentAmount);
                        calculateCurrentAmounts();
                        console.log("message: " + data.message);
                    }
                },
                error: function (error) {
                    alert(error.responseJSON.message);
                }
            });
        }

        function removeItems() {
            var rowsCount = $('#grid_FinancialCustodyDetails').children().length;
            var rowsIds = [];
            var rowsIndexs = [];
            flag = true;
            for (var rowIndex = 0; rowIndex < rowsCount - 1; rowIndex++) {
                checkDelete = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .checkDelete').is(":checked");
                id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .id').val();
                if (checkDelete) {
                    rowsIds.push(id);
                    rowsIndexs.push(rowIndex);
                }
            }
            if (flag && rowsIndexs.length > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });
                var formData = {
                    rowsIds: rowsIds
                };
                $.ajax({
                    type: 'DELETE',
                    url: '{!! route("financialCustodyItems.destroy", ["id" => 0, "employee_id" => $employee_id]) !!}',
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            $.each(rowsIndexs, function (arrIndex, rowIndex) {
                                RemoveRowAtIndex(rowIndex);
                            });
                            currentAmount.html(data.currentAmount);
                            calculateCurrentAmounts();
                            console.log("message: " + data.message);
                        }
                    },
                    error: function (error) {
                        console.log('Error: ', error);
                    }
                });
            } else {
                console.log("message: nothing to delete.");
            }
        }

        function acceptItems() {
            var rowsCount = $('#grid_FinancialCustodyDetails').children().length;
            var rowsIds = [];
            var rowsIndexs = [];
            flag = true;
            for (var i = 0; i < rowsCount - 1; i++) {
                if (IsValid(i)) {
                    rowsIds.push(id.val());
                    rowsIndexs.push(i);
                } else {
                    flag = false;
                }
            }
            if (flag) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });
                var formData = {
                    rowsIds: rowsIds
                };
                $.ajax({
                    type: 'POST',
                    url: '{!! route("financialCustodyItems.acceptItems", ["employee_id" => $employee_id]) !!}',
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            saveStatus.val(1);
                            $.each(rowsIndexs, function (arrIndex, rowIndex) {
                                console.log(rowIndex);
                                saveStatus = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .saveStatus');
                                saveStatus.val(2);
                                SetRowReadonly(rowIndex);
                            });
                            currentAmount.html(data.currentAmount);
                            calculateCurrentAmounts();
                            console.log("message: " + data.message);
                        }
                    },
                    error: function (error) {
                        console.log('Error: ', error);
                    }
                });
            }
        }

        function LockAll() {
            if ($("#canEdit").val() !== 1) {
                var rowsCount = $('#grid_FinancialCustodyDetails').children().length;
                for (var rowIndex = 0; rowIndex < rowsCount - 1; rowIndex++) {
                    if ($('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .saveStatus').val() === 2) {
                        SetRowReadonly(rowIndex);
                    }
                }
            }
        }

        function AddNewRow(CellChildInput) {
            SetCurrentRowIndex(CellChildInput);
            if ($(AfterCurrentRow).hasClass("ItemRow") === false) {
                $("#depositwithdrawTable").append('<tr class="gradeA odd ItemRow" role="row"> <td> <input type="checkbox" value=""> </td> <td hidden> <div class="form-group{{$errors->has("depositValue") ? " has-error" : ""}}">{{Form::text("depositValue", null, array( "class"=> "form-control IsNumberDecimal depositValue", "id"=> "", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("depositValue")) <label for="inputError" class="control-label">{{$errors->first("depositValue")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("withdrawValue") ? " has-error" : ""}}">{{Form::text("withdrawValue", null, array( "class"=> "form-control IsNumberDecimal withdrawValue", "id"=> "", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("withdrawValue")) <label for="inputError" class="control-label">{{$errors->first("withdrawValue")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("recordDesc") ? " has-error" : ""}}">{{Form::text("recordDesc", null, array( "class"=> "form-control recordDesc", "id"=> "", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("recordDesc")) <label for="inputError" class="control-label">{{$errors->first("recordDesc")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("cbo_processes") ? " has-error" : ""}}">{{Form::select("cbo_processes", [], null, array( "class"=> "form-control cbo_processes", "placeholder"=> "", "id"=> "", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("cbo_processes")) <label for="inputError" class="control-label">{{$errors->first("cbo_processes")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("client_id") ? " has-error" : ""}}">{{Form::select("client_id", [], null, array( "class"=> "form-control client_id", "placeholder"=> "", "id"=> "client_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("client_id")) <label for="inputError" class="control-label">{{$errors->first("client_id")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("supplier_id") ? " has-error" : ""}}">{{Form::select("supplier_id", [], null, array( "class"=> "form-control supplier_id", "placeholder"=> "", "id"=> "supplier_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("supplier_id")) <label for="inputError" class="control-label">{{$errors->first("supplier_id")}}</label> @endif </div></td><td hidden> <div class="form-group{{$errors->has("employee_id") ? " has-error" : ""}}">{{Form::select("employee_id", $employees, null, array( "class"=> "form-control employee_id", "placeholder"=> "", "id"=> "emplyee_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("employee_id")) <label for="inputError" class="control-label">{{$errors->first("employee_id")}}</label> @endif </div></td><td hidden> <div class="form-group{{$errors->has("expenses_id") ? " has-error" : ""}}">{{Form::select("expenses_id", [], null, array( "class"=> "form-control expenses_id", "placeholder"=> "", "id"=> "", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("expenses_id")) <label for="inputError" class="control-label">{{$errors->first("expenses_id")}}</label> @endif </div></td> <td> <div class="form-group{{$errors->has("notes") ? " has-error" : ""}}">{{Form::text("notes", null, array( "class"=> "form-control notes", "id"=> "notes", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("notes")) <label for="inputError" class="control-label">{{$errors->first("notes")}}</label> @endif </div></td><td hidden><input type="hidden" class="id" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="-1"></td><td hidden> <input type="hidden" class="saveStatus" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="0"> </td></tr>');
                var rowIndex = $("#depositwithdrawTable tr").length - 3;
                console.log(rowIndex);
                client_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .client_id');
                supplier_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .supplier_id');
                client_id.empty();
                client_id.append($("<option></option>"));
                supplier_id.empty();
                supplier_id.append($("<option></option>"));
                $.each(clients, function (clientId, client) {
                    if (client.hasOpenProcess || loadAll) {
                        client_id.append($("<option></option>").attr("value", clientId).text(client.name));
                    }
                });
                $.each(suppliers, function (supplierId, supplier) {
                    if (supplier.hasOpenProcess || loadAll) {
                        supplier_id.append($("<option></option>").attr("value", supplierId).text(supplier.name));
                    }
                });
                DoChange(currentRowIndex, CurrentCellName);
            } else {
                DoChange(currentRowIndex, CurrentCellName);
            }
        }

        function DoChange(rowIndex, cellName) {
            checkDelete = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .checkDelete');
            depositValue = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .depositValue');
            withdrawValue = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .withdrawValue');
            cbo_processes = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .cbo_processes');
            client_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .client_id');
            supplier_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .supplier_id');
            employee_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .employee_id');
            expenses_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .expenses_id');
            switch (cellName) {
                case "depositValue":
                    withdrawValue.val('');
                    supplier_id.val('');
                    employee_id.val('');
                    expenses_id.empty();
                    expenses_id.append($("<option></option>"));
                    break;
                case "withdrawValue":
                    depositValue.val('');
                    employee_id.val('');
                    expenses_id.empty();
                    expenses_id.append($("<option></option>"));
                    $.each(expenses, function (i, expense) {
                        expenses_id.append('<option value="' + expense.id + '">' + expense.name + '</option>');
                    });
                    break;
                case "recordDesc":
                    break;
                case "cbo_processes":
                    break;
                case "client_id":
                    supplier_id.val('');
                    employee_id.val('');
                    expenses_id.val('');
                    cbo_processes.empty();
                    cbo_processes.append($("<option></option>").attr("value", -1).text(''));
                    $.each(clients[client_id.val()].processes, function (processId, process) {
                        if (process.status === 'active' || loadAll) {
                            cbo_processes.append($("<option></option>").attr("value", processId).text(process.name));
                        }
                    });
                    break;
                case "supplier_id":
                    client_id.val('');
                    employee_id.val('');
                    expenses_id.val('');
                    depositValue.val('');
                    cbo_processes.empty();
                    cbo_processes.append($("<option></option>").attr("value", -1).text(''));
                    $.each(suppliers[supplier_id.val()].processes, function (processId, process) {
                        if (process.status === 'active' || loadAll) {
                            cbo_processes.append($("<option></option>").attr("value", processId).text(process.name));
                        }
                    });
                    break;
                case "employee_id":
                    client_id.val('');
                    supplier_id.val('');
                    expenses_id.val('');
                    cbo_processes.empty();
                    expenses_id.empty();
                    expenses_id.append($("<option></option>"));
                    if (employee_id.val() > 0) {
                        if (depositValue.val() > 0) {
                            $.each(employeeActions, function (i, employeeAction) {
                                if (employeeAction.id === 2 || employeeAction.id === 6) {
                                    expenses_id.append('<option value="' + employeeAction.id + '">' + employeeAction.name + '</option>');
                                }
                            });
                        } else {
                            $.each(employeeActions, function (i, employeeAction) {
                                if (employeeAction.id !== 2 && employeeAction.id !== 4) {
                                    expenses_id.append('<option value="' + employeeAction.id + '">' + employeeAction.name + '</option>');
                                }
                            });
                        }
                    } else {
                        depositValue.val('');
                        $.each(expenses, function (i, expense) {
                            expenses_id.append('<option value="' + expense.id + '">' + expense.name + '</option>');
                        });
                    }
                    break;
                case "expenses_id":
                    client_id.val('');
                    supplier_id.val('');
                    if (employee_id.val() === 0 && expenses_id.val() !== 2) {
                        depositValue.val('');
                    }
                    cbo_processes.empty();
                    break;
                case "notes":
                    break;
                default:
                    break;
            }
        }

        function RemoveRowAtIndex(rowIndex) {
            $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ')').remove();
        }

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
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

        function SetLastRowIndex() {
            lastRowIndex = currentRowIndex;
        }

        function SetRowReadonly(rowIndex) {
            checkDelete = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .checkDelete');
            depositValue = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .depositValue');
            withdrawValue = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .withdrawValue');
            recordDesc = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .recordDesc');
            cbo_processes = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .cbo_processes');
            client_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .client_id');
            supplier_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .supplier_id');
            employee_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .employee_id');
            expenses_id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .expenses_id');
            notes = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .notes');
            id = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .id');
            saveStatus = $('#grid_FinancialCustodyDetails tr:eq(' + rowIndex + ') .saveStatus');

            checkDelete.attr("disabled", "disabled");
            depositValue.attr("disabled", "disabled");
            withdrawValue.attr("disabled", "disabled");
            recordDesc.attr("disabled", "disabled");
            cbo_processes.attr("disabled", "disabled");
            client_id.attr("disabled", "disabled");
            supplier_id.attr("disabled", "disabled");
            employee_id.attr("disabled", "disabled");
            expenses_id.attr("disabled", "disabled");
            notes.attr("disabled", "disabled");
        }

        function calculateCurrentAmounts() {
            var rowsCount = $('#grid_FinancialCustodyDetails').children().length;
            var withdrawAmount = 0;
            for (var i = 0; i < rowsCount - 1; i++) {
                withdrawAmount += parseStringInt($('#grid_FinancialCustodyDetails tr:eq(' + i + ') .withdrawValue').val());
            }
            withdrawsAmount.html(withdrawAmount.toString());
        }

        function parseStringInt(val) {
            var parsedVal = parseInt(val);
            return isNaN(parsedVal) ? 0 : parsedVal;
        }

    </script>
@endsection