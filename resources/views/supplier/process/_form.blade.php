@section('script_taxes')
<script>
    var TaxesRate = {{ \App\Http\Controllers\FacilityController::TaxesRate() }};</script>
@endsection
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات العملية
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group{{ $errors->has('supplier_id') ? ' has-error' : '' }}">
                    {{ Form::label('supplier_id', 'اسم المورد') }}
                    {{ Form::select('supplier_id', $suppliers, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم المورد')
                        )
                    }}
                    @if ($errors->has('supplier_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('supplier_id') }}
                    </label>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('client_id') ? ' has-error' : '' }}">
                    {{ Form::label('client_id', 'اسم العميل') }}
                    {{ Form::select('client_id', $clients, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم العميل')
                        )
                    }}
                    @if ($errors->has('client_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('client_id') }}
                    </label>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('client_process_id') ? ' has-error' : '' }}">
                    {{ Form::label('client_process_id', 'اسم العملية') }} 
                    {{ Form::select('client_process_id', [], null,
                        array(
                            'id' => 'client_process_id',
                            'class' => 'form-control',
                            'placeholder' => 'اختر اسم العملية')
                        )
                    }}
                    @if ($errors->has('client_process_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('client_process_id') }}
                    </label>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                    {{ Form::label('employee_id', 'مشرف العملية') }} 
                    {{ Form::select('employee_id', $employees, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل مشرف العملية')
                        )
                    }}
                    @if ($errors->has('employee_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('employee_id') }}
                    </label>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('notes') ? ' has-error' : '' }}">
                    {{ Form::label('notes', 'ملاحظات') }} 
                    {{ Form::text('notes', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل ملاحظات')
                        )
                    }}
                    @if ($errors->has('notes'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('notes') }}
                    </label>
                    @endif
                </div>

                <div class="col-lg-12 no-padding">
                    <div class="legend">
                        {{ Form::checkbox('has_discount', '1', null, 
                            array(
                                'id' => 'has_discount',
                                'class' => 'checkbox_show_input'
                            )
                        ) }} 
                        {{ Form::label('has_discount', 'خصم') }}
                    </div>
                    <div class="hidden_input">

                        <div class="col-lg-5">
                            <div class="form-group{{ $errors->has('discount_percentage') ? ' has-error' : '' }}">
                                {{ Form::label('discount_percentage', 'نسبة الخصم') }}
                                <div class="input-group">
                                    {{ Form::text('discount_percentage', null, 
                                        array(
                                            'id' => 'discount_percentage',
                                            'class' => 'form-control', 
                                            'placeholder' => 'ادخل النسبة'
                                            )
                                        )
                                    }}
                                    <span class="input-group-addon">
                                        %
                                    </span>    
                                </div><!-- /input-group -->
                                @if ($errors->has('discount_percentage'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('discount_percentage') }}
                                </label>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group{{ $errors->has('discount_reason') ? ' has-error' : '' }}">
                                {{ Form::label('discount_reason', 'سبب الخصم') }} 
                                {{ Form::text('discount_reason', null, 
                                    array(
                                        'class' => 'form-control', 
                                        'placeholder' => 'ادخل سبب الخصم')
                                    )
                                }}
                                @if ($errors->has('discount_reason'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('discount_reason') }}
                                </label>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group{{ $errors->has('discount_value') ? ' has-error' : '' }}">
                                {{ Form::label('discount_value', 'القيمة') }} 
                                {{ Form::text('discount_value', null, 
                                    array(
                                        'class' => 'form-control', 
                                        'placeholder' => 'ادخل القيمة'
                                        )
                                    )
                                }}
                                @if ($errors->has('discount_value'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('discount_value') }}
                                </label>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-12 no-padding">
                    <div class="legend">
                        {{ Form::checkbox('require_bill', '1', null, 
                            array(
                                'id' => 'require_bill',
                            )
                        ) }} 
                        {{ Form::label('require_bill', 'فاتورة') }}
                    </div>
                </div>

                {{ Form::hidden('total_price') }}
                {{ Form::hidden('total_price_taxes') }}
                {{ Form::hidden('taxes_value') }}

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>
                    <span>مبلغ العملية </span>
                    <span class="price process_price">0</span>
                </h4>
                <h4>
                    <span>مبلغ الخصم </span>
                    <span class="price discount_price">0</span>
                </h4>
                <h4>
                    <span>ضريبة مبيعات </span>
                    <span class="price taxes_price">0</span>
                </h4>
                <hr>
                <h4>
                    <span>القيمة اﻻجمالية </span>
                    <span class="price final_price">0</span>
                </h4>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <button class="btn btn-lg btn-block btn-success" type="submit">
                @if(isset($model))
                تعديل بيانات عملية
                @else
                أضف عملية جديد
                @endif
            </button>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                مواصفات العملية
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
                            <tr class="{{ ($i != count($items) - 1) ? 'skip' : '' }}" >
                                {{ Form::hidden('items['.$i.'][id]') }}
                                <td>
                                    <div class="form-group{{ $errors->has('items.'.$i.'.description') ? ' has-error' : '' }}">
                                        {{ Form::text('items['.$i.'][description]', $items[$i]['description'], 
                                            array(
                                                'class' => 'form-control', 
                                                'placeholder' => 'ادخل تفاصيل البيان')
                                            )
                                        }}
                                        @if ($errors->has('items.'.$i.'.description'))
                                        <label for="inputError" class="control-label">
                                            {{ $errors->first('items.'.$i.'.description') }}
                                        </label>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group{{ $errors->has('items.'.$i.'.quantity') ? ' has-error' : '' }}">
                                        {{ Form::text('items['.$i.'][quantity]', $items[$i]['quantity'], 
                                            array(
                                                'class' => 'form-control quantity', 
                                                'placeholder' => 'ادخل الكمية')
                                            )
                                        }}
                                        @if ($errors->has('items.'.$i.'.quantity'))
                                        <label for="inputError" class="control-label">
                                            {{ $errors->first('items.'.$i.'.quantity') }}
                                        </label>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group{{ $errors->has('items.'.$i.'.unit_price') ? ' has-error' : '' }}">
                                        {{ Form::text('items['.$i.'][unit_price]', $items[$i]['unit_price'], 
                                            array(
                                                'class' => 'form-control unit_price', 
                                                'placeholder' => 'ادخل سعر الوحدة')
                                            )
                                        }}
                                        @if ($errors->has('items.'.$i.'.unit_price'))
                                        <label for="inputError" class="control-label">
                                            {{ $errors->first('items.'.$i.'.unit_price') }}
                                        </label>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group{{ $errors->has('items.'.$i.'.total_price') ? ' has-error' : '' }}">
                                        {{ Form::text('items['.$i.'][total_price]', $items[$i]['total_price'], 
                                            array(
                                                'class' => 'form-control total_price')
                                            )
                                        }}
                                        @if ($errors->has('items.'.$i.'.total_price'))
                                        <label for="inputError" class="control-label">
                                            {{ $errors->first('items.'.$i.'.total_price') }}
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

<script>
    var clientProcesses = [@foreach($clientProcesses as $k => $info) { id: '{{ $k }}', name: '{{ $info["name"] }}', client_id: '{{ $info["client_id"] }}'}, @endforeach];
    var cbo_processes = $('#client_process_id');
    var client_id = $('#client_id');
    $(document).ready(function ($) {
    $('#client_id').change(function () {

    cbo_processes.empty();
    cbo_processes.append($("<option></option>"));
    $.each(clientProcesses, function (key, process) {
    //console.log(process);
    if (process.client_id == client_id.val()) {
    cbo_processes.append($("<option></option>").attr("value", process.id).text(process.name));
    }
    });
    });
    });
    LoadProcess();
    function LoadProcess() {
    var clientprocesses_val = "@if(isset($model)) {{ $process->client_process_id }} @else -1 @endif"; //$("#client_process_id").val();
    //alert(clientprocesses_val);
    cbo_processes.empty();
    cbo_processes.append($("<option></option>"));
    $.each(clientProcesses, function (key, process) {
    //console.log(process);
    if (process.client_id == client_id.val()) {
    if (process.id == clientprocesses_val) {
    cbo_processes.append($("<option selected='selected'></option>").attr("value", process.id).text(process.name));
    } else {
    cbo_processes.append($("<option></option>").attr("value", process.id).text(process.name));
    }
    }
    });
    }
</script>