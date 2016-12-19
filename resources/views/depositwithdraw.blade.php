@extends('layouts.app')

@section('title', 'وارد / منصرف')

@section('content')
<style>
    @media(min-width:320px) and (max-width:1440px) {
        #dataTables-example_wrapper .col-sm-12 form {
            overflow: auto;
        }
        .index .dataTables_wrapper .row {
            margin-bottom: 10px;
        }
    }

    @media(min-width:320px) and (max-width:1295px) {
        #dataTables-example_wrapper .col-sm-12 form {
            overflow: auto;
        }
        .index .dataTables_wrapper .row {
            margin-bottom: 10px;
        }
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">وارد / منصرف</h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa  fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['clients_number'] }}</div>
                        <div>عملاء</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('client') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['suppliers_number'] }}</div>
                        <div>موردين</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('supplier') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['process_number'] }}</div>
                        <div>عمليات العملاء</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('client/process') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['Supplierprocess_number'] }}</div>
                        <div>عمليات الموردين</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('supplier/process') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                وارد / منصرف
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

                <div class="dataTable_wrapper">
                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="date"> 
                                    <div align="center">
                                        <table width="100%" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" border="0"  > 
                                            <tr> 
                                                <td class="alt1"> 
                                                    <div class="alt2Active" style="padding:6px; overflow:auto"> 
                                                        <div id="postmenu_344900"> 
                                                            <!-- clock hack --> 
                                                            <div id="clock">Loading...</div> 
                                                            <script type="text/javascript">
                                                                function refrClock()
                                                                {
                                                                    var d = new Date();
                                                                    var day = d.getDay();
                                                                    var date = d.getDate();
                                                                    var month = d.getMonth();
                                                                    var year = d.getFullYear();
                                                                    var days = new Array("الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت");
                                                                    var months = new Array("يناير", "فبراير", "مارس", "ابريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "اكتوبر", "نوفمبر", "ديسمبر");
                                                                    document.getElementById("clock").innerHTML = days[day] + " " + date + " " + months[month] + " " + year;
                                                                    setTimeout("refrClock()", 1000);
                                                                }
                                                                refrClock();
                                                            </script> 
                                                        </div>
                                                    </div>
                                                </td> 
                                            </tr> 
                                        </table> 
                                    </div>
                                </label>
                            </div>

                            <div class="col-sm-6 text-left">
                                <div id="dataTables-example_filter" class="dataTables_filter">
                                    <button type="button" class="btn btn-primary disabled"> 
                                        <label>الرصيد الحالى : <span id="currentAmount">{{ $numbers['current_amount'] }}</span> جنيه</label>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {{ Form::open(['route' => 'depositwithdraw.store', 'id' => 'depositwithdrawForm', "role" => "form"]) }}

                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="depositwithdrawTable" role="grid" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th rowspan="1" colspan="1" style="width:20px;" >اختيار</th>
                                            <th rowspan="1" colspan="1" >وارد</th>
                                            <th rowspan="1" colspan="1" >منصرف</th>
                                            <th rowspan="1" colspan="1" >بيان</th>
                                            <th rowspan="1" colspan="1" >اسم العملية</th>
                                            <th rowspan="1" colspan="1" >اسم العميل</th>
                                            <th rowspan="1" colspan="1" >اسم المورد</th>
                                            <th rowspan="1" colspan="1" >اسم الموظف</th>
                                            <th rowspan="1" colspan="1" >اسم المصروف</th>
                                            <th rowspan="1" colspan="1" >طريقة الدفع</th>
                                            <th rowspan="1" colspan="1" >ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grid_GuardianshipDetails">
                                        @forelse ($depositWithdraws as $depositWithdraw)
                                        <tr class="gradeA odd ItemRow" role="row">
                                            <td>
                                                <input type="checkbox" value="">
                                            </td>
                                            <td>
                                                <div class="form-group{{ $errors->has("depositValue") ? " has-error" : "" }}">
                                                    {{ Form::text("depositValue", ($depositWithdraw->depositValue > 0 ? $depositWithdraw->depositValue : null), 
                                                                array(
                                                                    "class" => "form-control IsNumberOnly", 
                                                                    "id" => "depositValue",
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
                                                    {{ Form::text("withdrawValue", ($depositWithdraw->withdrawValue > 0 ? $depositWithdraw->withdrawValue : null), 
                                                                array(
                                                                    "class" => "form-control IsNumberOnly", 
                                                                    "id" => "withdrawValue",
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
                                                    {{ Form::text("recordDesc", $depositWithdraw->recordDesc, 
                                                                array(
                                                                    "class" => "form-control", 
                                                                    "id" => "recordDesc",
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
                                                    {{ Form::select("cbo_processes", [], $depositWithdraw->cbo_processes, 
                                                                array(
                                                                    "class" => "form-control",
                                                                    "placeholder" => "",
                                                                    "id" => "client_id",
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
                                                    {{ Form::select("client_id", $clients, $depositWithdraw->client_id,
                                                        array(
                                                            "class" => "form-control",
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
                                                    {{ Form::select("supplier_id", $suppliers, $depositWithdraw->supplier_id,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "supplier_id",
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
                                            <td> 
                                                <div class="form-group{{ $errors->has("employee_id") ? " has-error" : "" }}">
                                                    {{ Form::select("employee_id", $employees, $depositWithdraw->employee_id,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "emplyee_id",
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
                                            <td>
                                                <div class="form-group{{ $errors->has("expenses_id") ? " has-error" : "" }}">
                                                    {{ Form::select("expenses_id", $expenses, $depositWithdraw->expenses_id,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "expenses_id",
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
                                                <div class="form-group{{ $errors->has("payMethod") ? " has-error" : "" }}">
                                                    {{ Form::select("payMethod", $payMethods, $depositWithdraw->payMethod,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "payMethod",
                                                            "style" => "width:85px;",
                                                            "onchange" => "AddNewRow(this)",
                                                            "onblur" => "OnRowLeave(this)",
                                                            "onfocus" => "OnRowFocus(this)")
                                                        )
                                                    }}
                                                    @if ($errors->has("payMethod"))
                                                    <label for="inputError" class="control-label">
                                                        {{ $errors->first("payMethod") }}
                                                    </label>
                                                    @endif
                                                </div>

                                            </td>
                                            <td>
                                                <div class="form-group{{ $errors->has("notes") ? " has-error" : "" }}">
                                                    {{ Form::text("notes", $depositWithdraw->notes, 
                                                                array(
                                                                    "class" => "form-control", 
                                                                    "id" => "notes",
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
                                                <input type="hidden" name="id" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="{{ $depositWithdraw->id }}">
                                            </td>
                                            <td hidden>
                                                <input type="hidden" name="saveStatus" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="{{ $depositWithdraw->saveStatus }}">
                                            </td>
                                        </tr>
                                        @empty
                                        @endforelse
                                        <tr class="gradeA odd ItemRow" role="row">
                                            <td>
                                                <input type="checkbox" value="">
                                            </td>
                                            <td>
                                                <div class="form-group{{ $errors->has("depositValue") ? " has-error" : "" }}">
                                                    {{ Form::text("depositValue", null, 
                                                                array(
                                                                    "class" => "form-control IsNumberOnly", 
                                                                    "id" => "client_id",
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
                                                                    "class" => "form-control IsNumberOnly", 
                                                                    "id" => "withdrawValue",
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
                                                                    "class" => "form-control", 
                                                                    "id" => "recordDesc",
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
                                                                    "class" => "form-control",
                                                                    "placeholder" => "",
                                                                    "id" => "client_id",
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
                                                    {{ Form::select("client_id", $clients, null,
                                                        array(
                                                            "class" => "form-control",
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
                                                    {{ Form::select("supplier_id", $suppliers, null,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "supplier_id",
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
                                            <td> 
                                                <div class="form-group{{ $errors->has("employee_id") ? " has-error" : "" }}">
                                                    {{ Form::select("employee_id", $employees, null,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "emplyee_id",
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
                                            <td>
                                                <div class="form-group{{ $errors->has("expenses_id") ? " has-error" : "" }}">
                                                    {{ Form::select("expenses_id", $expenses, null,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "expenses_id",
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
                                                <div class="form-group{{ $errors->has("payMethod") ? " has-error" : "" }}">
                                                    {{ Form::select("payMethod", $payMethods, null,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "payMethod",
                                                            "style" => "width:85px;",
                                                            "onchange" => "AddNewRow(this)",
                                                            "onblur" => "OnRowLeave(this)",
                                                            "onfocus" => "OnRowFocus(this)")
                                                        )
                                                    }}
                                                    @if ($errors->has("payMethod"))
                                                    <label for="inputError" class="control-label">
                                                        {{ $errors->first("payMethod") }}
                                                    </label>
                                                    @endif
                                                </div>

                                            </td>
                                            <td>
                                                <div class="form-group{{ $errors->has("notes") ? " has-error" : "" }}">
                                                    {{ Form::text("notes", null, 
                                                                array(
                                                                    "class" => "form-control", 
                                                                    "id" => "notes",
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
                                                <input type="hidden" name="id" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="-1">
                                            </td>
                                            <td hidden>
                                                <input type="hidden" name="saveStatus" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="0">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" id="canEdit" name="canEdit" value="{{ $canEdit }}" />
                                {{ Form::close() }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url("/depositwithdraw") }}" class="btn btn-success">جديد</a>
                            <button type="button" class="btn btn-danger" onclick="RemoveSelected()">حذف</button>
                            <button type="button" class="btn btn-primary" onclick="LockSaveToAll()">حفظ</button>
                        </div>
                        <div class="col-md-6 text-left">
                            @if(Entrust::ability('admin', 'deposit-withdraw-edit'))
                            <!-- Date Picker-->
                            {{ Form::open(['route' => 'depositwithdraw.search', 'method' => 'get']) }}
                            <input type="text" style="width:100px"  id="targetdate" name="targetdate" readonly class="form-control">
                            <script>
                                $(function () {
                                    $("#targetdate").datepicker();
                                });
                            </script>
                            <button type="submit" class="btn btn-danger">بحث</button>
                            {{ Form::close() }}
                            @endif
                        </div>
                    </div>
                </div> 
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<script>
    var checkDelete, depositValue, withdrawValue, cbo_processes, client_id, supplier_id, employee_id, expenses_id, recordDesc, notes, payMethod, saveStatus, id, flag, canEdit, currentAmount;
    var CurrentCell, CurrentCellName, CurrentRow, AfterCurrentRow, currentRowIndex, lastRowIndex = -1, rowCount = 1;
    SetIsNumberOnly();
    LockAll();
    currentAmount = $("#currentAmount");

    function OnRowFocus(CellChildInput) {
        SetCurrentRowIndex(CellChildInput);
        if (currentRowIndex != lastRowIndex && lastRowIndex > -1) {
            // check valdiation
            // // Save
            //console.log('check valdiation');
            if (IsValid(lastRowIndex)) {
                SaveCurrentRow(lastRowIndex);
                //console.log('leave and save');
            }
            lastRowIndex = -1;
        } else {
            //console.log('still edit');
            SetLastRowIndex();
        }
    }

    function OnRowLeave(CellChildInput) {
        SetCurrentRowIndex(CellChildInput);
        //console.log('currentRowIndex < rowCount: ' + currentRowIndex + ', ' + rowCount);
        if (currentRowIndex >= rowCount - 1) {
            //console.log('last row');
            lastRowIndex = -1;
        } else {
            //console.log('still edit');
            SetLastRowIndex();
        }
    }

    function IsValid(rowIndex) {
        if (rowIndex == -1) {
            return false;
        }
        checkDelete = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(0)').children(0);
        depositValue = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(1)').children(0).children(0);
        withdrawValue = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(2)').children(0).children(0);
        recordDesc = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(3)').children(0).children(0);
        cbo_processes = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(4)').children(0).children(0);
        client_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(5)').children(0).children(0);
        supplier_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(6)').children(0).children(0);
        employee_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(7)').children(0).children(0);
        expenses_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(8)').children(0).children(0);
        payMethod = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(9)').children(0).children(0);
        notes = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(10)').children(0).children(0);
        id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(11)').children(0);
        saveStatus = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(12)').children(0);
        //console.log("saveStatus in IsValid: " + saveStatus.val());
        /*if (saveStatus.val() == 0) {
         return false;
         }*/
        var flag = true;
        if (recordDesc.val() == '') {
            recordDesc.parent().addClass("has-error");
            flag = false;
        } else {
            recordDesc.parent().removeClass("has-error");
        }
        if (payMethod.val() == '') {
            payMethod.parent().addClass("has-error");
            flag = false;
        } else {
            payMethod.parent().removeClass("has-error");
        }
        if (supplier_id.val() === '' && employee_id.val() === '' && expenses_id.val() === '' && client_id.val() === '' && (cbo_processes.val() === '' || cbo_processes.val() === null)) {
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
        //console.log("depositValue.val(): " + depositValue.val() + ", : " + (supplier_id.val() === '' && employee_id.val() === '' && expenses_id.val() === '' && client_id.val() === '' && (cbo_processes.val() === '' || cbo_processes.val() === null)) + ", " + employee_id.val());
        //console.log(" supplier_id: " + supplier_id.val() + ", employee_id: " + employee_id.val() + ", expenses_id: " + expenses_id.val() + ", client_id: " + client_id.val() + ", cbo_processes: " + cbo_processes.val() + ", rowIndex: " + rowIndex);
        if (depositValue.val() === '' && withdrawValue.val() === '') {
            depositValue.parent().addClass("has-error");
            withdrawValue.parent().addClass("has-error");
            flag = false;
        } else {
            depositValue.parent().removeClass("has-error");
            withdrawValue.parent().removeClass("has-error");
        }
        return flag;
    }

    function SaveCurrentRow(rowIndex) {
        //console.log('will save');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        canEdit = $("#canEdit").val();
        var formData = {
            checkDelete: checkDelete.val(),
            depositValue: depositValue.val(),
            withdrawValue: withdrawValue.val(),
            recordDesc: recordDesc.val(),
            cbo_processes: cbo_processes.val(),
            client_id: client_id.val(),
            supplier_id: supplier_id.val(),
            employee_id: employee_id.val(),
            expenses_id: expenses_id.val(),
            payMethod: payMethod.val(),
            notes: notes.val(),
            saveStatus: saveStatus.val()
        }
        //used to determine the http verb to use [add=POST], [update=PUT]
        var type = "POST"; //for creating new resource
        var saveurl = '{{ url("/depositwithdraw") }}';
        if (saveStatus.val() == 1 || (saveStatus.val() == 2 && canEdit == 1)) {
            type = "PUT"; //for updating existing resource
            saveurl += '/' + id.val();
        }
        //console.log(formData);
        $.ajax({
            type: type,
            url: saveurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                //console.log("Success: " + data);
                //console.log("Success: " + data.message);
                if (data.success) {
                    saveStatus.val(1);
                    id.val(data.id);
                    currentAmount.html(data.current_amount);
                    console.log("message: " + data.message);
                }
            },
            error: function (error) {
                console.log('Error: ', error);
            }
        });
    }

    function RemoveSelected() {
        var rowsCount = $('#grid_GuardianshipDetails').children().length;
        var rowsIds = [];
        var rowsIndexs = [];
        flag = true;
        for (var rowIndex = 0; rowIndex < rowsCount - 1; rowIndex++) {
            checkDelete = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(0)').children(0).is(":checked");
            id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(11)').children(0).val();
            //console.log(checkDelete);
            if (checkDelete) {
                rowsIds.push(id);
                //console.log(rowIndex);
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
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var type = "POST"; //for creating new resource
            var saveurl = '{{ url("/depositwithdraw/RemoveSelected") }}';
            //console.log(formData);
            $.ajax({
                type: type,
                url: saveurl,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    //console.log("Success: " + data);
                    //console.log("Success: " + data.message);
                    if (data.success) {
                        $.each(rowsIndexs, function (arrIndex, rowIndex) {
                            //console.log(value);
                            RemoveRowAtIndex(rowIndex);
                        });
                        currentAmount.html(data.current_amount);
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

    function LockSaveToAll() {
        var rowsCount = $('#grid_GuardianshipDetails').children().length;
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
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var type = "POST"; //for creating new resource
            var saveurl = '{{ url("/depositwithdraw/LockSaveToAll") }}';
            //console.log(formData);
            $.ajax({
                type: type,
                url: saveurl,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    //console.log("Success: " + data);
                    //console.log("Success: " + data.message);
                    if (data.success) {
                        saveStatus.val(1);
                        //id.val(data.id);
                        //console.log(data.rowsIds);
                        $.each(rowsIndexs, function (arrIndex, rowIndex) {
                            console.log(rowIndex);
                            saveStatus = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(12)').children(0);
                            saveStatus.val(2);
                            SetRowReadonly(rowIndex);
                        });
                        currentAmount.html(data.current_amount);
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
        if ($("#canEdit").val() != 1) {
            var rowsCount = $('#grid_GuardianshipDetails').children().length;
            for (var rowIndex = 0; rowIndex < rowsCount - 1; rowIndex++) {
                if ($('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(12)').children(0).val() == 2) {
                    SetRowReadonly(rowIndex);
                }
            }
        }
    }

    function AddNewRow(CellChildInput) {
        SetCurrentRowIndex(CellChildInput);
        if ($(AfterCurrentRow).hasClass("ItemRow") == false) {
            $("#depositwithdrawTable").append('<tr class="gradeA odd ItemRow" role="row"> <td> <input type="checkbox" value=""> </td><td> <div class="form-group{{$errors->has("depositValue") ? " has-error" : ""}}">{{Form::text("depositValue", null, array( "class"=> "form-control IsNumberOnly", "id"=> "client_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("depositValue")) <label for="inputError" class="control-label">{{$errors->first("depositValue")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("withdrawValue") ? " has-error" : ""}}">{{Form::text("withdrawValue", null, array( "class"=> "form-control IsNumberOnly", "id"=> "withdrawValue", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("withdrawValue")) <label for="inputError" class="control-label">{{$errors->first("withdrawValue")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("recordDesc") ? " has-error" : ""}}">{{Form::text("recordDesc", null, array( "class"=> "form-control", "id"=> "recordDesc", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("recordDesc")) <label for="inputError" class="control-label">{{$errors->first("recordDesc")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("cbo_processes") ? " has-error" : ""}}">{{Form::select("cbo_processes", [], null, array( "class"=> "form-control", "placeholder"=> "", "id"=> "client_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("cbo_processes")) <label for="inputError" class="control-label">{{$errors->first("cbo_processes")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("client_id") ? " has-error" : ""}}">{{Form::select("client_id", $clients, null, array( "class"=> "form-control", "placeholder"=> "", "id"=> "client_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("client_id")) <label for="inputError" class="control-label">{{$errors->first("client_id")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("supplier_id") ? " has-error" : ""}}">{{Form::select("supplier_id", $suppliers, null, array( "class"=> "form-control", "placeholder"=> "", "id"=> "supplier_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("supplier_id")) <label for="inputError" class="control-label">{{$errors->first("supplier_id")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("employee_id") ? " has-error" : ""}}">{{Form::select("employee_id", $employees, null, array( "class"=> "form-control", "placeholder"=> "", "id"=> "emplyee_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("employee_id")) <label for="inputError" class="control-label">{{$errors->first("employee_id")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("expenses_id") ? " has-error" : ""}}">{{Form::select("expenses_id", $expenses, null, array( "class"=> "form-control", "placeholder"=> "", "id"=> "expenses_id", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("expenses_id")) <label for="inputError" class="control-label">{{$errors->first("expenses_id")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("payMethod") ? " has-error" : ""}}">{{Form::select("payMethod", $payMethods, null, array( "class"=> "form-control", "placeholder"=> "", "id"=> "payMethod", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("payMethod")) <label for="inputError" class="control-label">{{$errors->first("payMethod")}}</label> @endif </div></td><td> <div class="form-group{{$errors->has("notes") ? " has-error" : ""}}">{{Form::text("notes", null, array( "class"=> "form-control", "id"=> "notes", "style"=> "width:85px;", "onchange"=> "AddNewRow(this)", "onblur"=> "OnRowLeave(this)", "onfocus"=> "OnRowFocus(this)") )}}@if ($errors->has("notes")) <label for="inputError" class="control-label">{{$errors->first("notes")}}</label> @endif </div></td><td hidden><input type="hidden" name="id" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="-1"></td><td hidden> <input type="hidden" name="saveStatus" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="0"> </td></tr>');
            SetIsNumberOnly();
            DoChange(currentRowIndex, CurrentCellName);
        } else {
            DoChange(currentRowIndex, CurrentCellName);
        }
    }

    function DoChange(rowIndex, cellName) {
        checkDelete = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(0)').children(0);
        depositValue = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(1)').children(0).children(0);
        withdrawValue = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(2)').children(0).children(0);
        cbo_processes = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(4)').children(0).children(0);
        client_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(5)').children(0).children(0);
        supplier_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(6)').children(0).children(0);
        employee_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(7)').children(0).children(0);
        expenses_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(8)').children(0).children(0);
        switch (cellName) {
            case "depositValue":
                withdrawValue.val('');
                supplier_id.val('');
                employee_id.val('');
                expenses_id.val('');
                break;
            case "withdrawValue":
                depositValue.val('');
                client_id.val('');
                employee_id.val('');
                expenses_id.val('');
                break;
            case "recordDesc":
                break;
            case "cbo_processes":
                break;
            case "client_id":
                supplier_id.val('');
                employee_id.val('');
                expenses_id.val('');
                withdrawValue.val('');
                $.get("{{ url('api/getClientProcesses/') }}", {option: client_id.val()},
                        function (data) {
                            cbo_processes.empty();
                            cbo_processes.append($("<option></option>").attr("value", -1).text(''));
                            $.each(data, function (key, value) {
                                cbo_processes.append($("<option></option>").attr("value", key).text(value));
                            });
                        });
                break;
            case "supplier_id":
                client_id.val('');
                employee_id.val('');
                expenses_id.val('');
                depositValue.val('');
                $.get("{{ url('api/getSupplierProcesses/') }}", {option: supplier_id.val()},
                        function (data) {
                            cbo_processes.empty();
                            cbo_processes.append($("<option></option>").attr("value", -1).text(''));
                            $.each(data, function (key, value) {
                                cbo_processes.append($("<option></option>").attr("value", key).text(value));
                            });
                        });
                break;
            case "employee_id":
                client_id.val('');
                supplier_id.val('');
                expenses_id.val('');
                depositValue.val('');
                cbo_processes.empty();
                break;
            case "expenses_id":
                client_id.val('');
                supplier_id.val('');
                depositValue.val('');
                cbo_processes.empty();
                break;
            case "payMethod":
                break;
            case "notes":
                break;
            default:
                break;
        }
    }

    function RemoveRowAtIndex(rowIndex) {
        $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ')').remove();
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

    function SetIsNumberOnly() {
        $(".IsNumberOnly").keypress(function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            AddNewRow(evt.currentTarget);
            return true;
        });
    }

    function SetRowReadonly(rowIndex) {
        checkDelete = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(0)').children(0);
        depositValue = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(1)').children(0).children(0);
        withdrawValue = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(2)').children(0).children(0);
        recordDesc = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(3)').children(0).children(0);
        cbo_processes = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(4)').children(0).children(0);
        client_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(5)').children(0).children(0);
        supplier_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(6)').children(0).children(0);
        employee_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(7)').children(0).children(0);
        expenses_id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(8)').children(0).children(0);
        payMethod = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(9)').children(0).children(0);
        notes = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(10)').children(0).children(0);
        id = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(11)').children(0);
        saveStatus = $('#grid_GuardianshipDetails tr:eq(' + rowIndex + ') td:eq(12)').children(0);

        checkDelete.attr("disabled", "disabled");
        depositValue.attr("disabled", "disabled");
        withdrawValue.attr("disabled", "disabled");
        recordDesc.attr("disabled", "disabled");
        cbo_processes.attr("disabled", "disabled");
        client_id.attr("disabled", "disabled");
        supplier_id.attr("disabled", "disabled");
        employee_id.attr("disabled", "disabled");
        expenses_id.attr("disabled", "disabled");
        payMethod.attr("disabled", "disabled");
        notes.attr("disabled", "disabled");
    }

</script>
@endsection