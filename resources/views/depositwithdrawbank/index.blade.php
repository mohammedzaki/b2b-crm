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
    tr.InSave {

    }
    tr.InSave td {
        background-color: red !important;
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
                                                                    document.getElementById("clock").innerHTML = days['{{ $numbers["current_dayOfWeek"] }}'] + " " + '{{ $numbers["current_dayOfMonth"] }}' + " " + months['{{ $numbers["current_month"] }}'] + " " + '{{ $numbers["current_year"] }}';
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
                            <div class="col-sm-4 text-left">
                                <div id="dataTables-example_filter" class="dataTables_filter">
                                    <button type="button" class="btn btn-primary disabled"> 
                                        <label>الرصيد السابق : <span id="currentAmount">{{ $numbers['currentDay_amountOff'] }}</span> جنيه</label>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-4 text-left">
                                <div id="dataTables-example_filter" class="dataTables_filter">
                                    <button type="button" class="btn btn-primary disabled"> 
                                        <label>الوارد : <span id="currentAmount">{{ $numbers['deposits_amount'] }}</span> جنيه</label>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-4 text-left">
                                <div id="dataTables-example_filter" class="dataTables_filter">
                                    <button type="button" class="btn btn-primary disabled"> 
                                        <label>المنصرف : <span id="currentAmount">{{ $numbers['withdraws_amount'] }}</span> جنيه</label>
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
                                    <tbody id="grid_FinancialCustodyDetails">
                                        @forelse ($depositWithdraws as $depositWithdraw)
                                        <tr class="gradeA odd ItemRow Saved" role="row">
                                            <td>
                                                <input type="checkbox" value="" class="checkDelete">
                                            </td>
                                            <td>
                                                <div class="form-group{{ $errors->has("depositValue") ? " has-error" : "" }}">
                                                    {{ Form::text("depositValue", ($depositWithdraw->depositValue > 0 ? $depositWithdraw->depositValue : null), 
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
                                                    {{ Form::text("withdrawValue", ($depositWithdraw->withdrawValue > 0 ? $depositWithdraw->withdrawValue : null), 
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
                                                    {{ Form::text("recordDesc", $depositWithdraw->recordDesc, 
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
                                                    {{ Form::select("cbo_processes", [$depositWithdraw->cbo_processes => $depositWithdraw->cbo_processes], $depositWithdraw->cbo_processes, 
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
                                                    {{ Form::select("client_id", [$depositWithdraw->client_id => $depositWithdraw->client_id], $depositWithdraw->client_id,
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
                                                    {{ Form::select("supplier_id", [$depositWithdraw->supplier_id => $depositWithdraw->supplier_id], $depositWithdraw->supplier_id,
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
                                            <td> 
                                                <div class="form-group{{ $errors->has("employee_id") ? " has-error" : "" }}">
                                                    {{ Form::select("employee_id", $employees, $depositWithdraw->employee_id,
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
                                            <td>
                                                <div class="form-group{{ $errors->has("expenses_id") ? " has-error" : "" }}">
                                                    {{ Form::select("expenses_id", [$depositWithdraw->expenses_id => $depositWithdraw->expenses_id], $depositWithdraw->expenses_id,
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
                                                <div class="form-group{{ $errors->has("payMethod") ? " has-error" : "" }}">
                                                    {{ Form::select("payMethod", $payMethods, $depositWithdraw->payMethod,
                                                        array(
                                                            "class" => "form-control payMethod",
                                                            "placeholder" => "",
                                                            "id" => "",
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
                                                <input type="hidden" class="id" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="{{ $depositWithdraw->id }}">
                                            </td>
                                            <td hidden>
                                                <input type="hidden" class="saveStatus" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="{{ $depositWithdraw->saveStatus }}">
                                            </td>
                                        </tr>
                                        @empty
                                        @endforelse
                                        <tr class="gradeA odd ItemRow" role="row">
                                            <td>
                                                <input type="checkbox" value="" class="checkDelete">
                                            </td>
                                            <td>
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
                                            <td> 
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
                                            <td>
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
                                                <div class="form-group{{ $errors->has("payMethod") ? " has-error" : "" }}">
                                                    {{ Form::select("payMethod", $payMethods, null,
                                                        array(
                                                            "class" => "form-control payMethod",
                                                            "placeholder" => "",
                                                            "id" => "",
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
                                                <input type="hidden" class="id" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="-1">
                                            </td>
                                            <td hidden>
                                                <input type="hidden" class="saveStatus" onchange="AddNewRow(this)" onblur="OnRowLeave(this)" onfocus="OnRowFocus(this)" value="0">
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
                            <button type="button" class="btn btn-danger" onclick="removeSelected()">حذف</button>
                            <button type="button" class="btn btn-primary" onclick="LockSaveToAll()">حفظ</button>
                        </div>
                        @if(Entrust::ability('admin', 'deposit-withdraw-edit'))
                        <!--<div class="col-md-5 text-left">
                            <label> تحميل الكل</label>
                            {{ Form::checkbox("loadAllProcessAndClients", "1", 0, array(
                                "id" => "loadAllProcessAndClients",
                                "class" => "checkbox_show_input",
                                "onchange" => "loadAllProcessAndClients()")) 
                            }} 
                        </div>-->
                        <div class="col-md-6 text-left">
                            <!-- Date Picker-->
                            {{ Form::open(['route' => 'depositwithdraw.search', 'method' => 'get']) }}
                            <input type="text" id="targetdate" name="targetdate" readonly class="form-control datepickerCommon">
                            <button type="submit" class="btn btn-danger">بحث</button>
                            {{ Form::close() }}
                        </div>
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

@endsection