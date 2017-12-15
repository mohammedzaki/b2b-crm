
@section('script_taxes')
<script>
    var TaxesRate = {{ \App\Http\Controllers\FacilityManagement\FacilityController::TaxesRate() / 100 }};</script>
</script>
@endsection

<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات العملية
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'اسم العملية') }} 
                    {{ Form::text('name', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل اسم العملية')
                        )
                    }}
                    @if ($errors->has('name'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('name') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('client_id') ? ' has-error' : '' }}">
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

                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
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

                <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
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
                        {{ Form::checkbox('has_source_discount', '1', null, 
                            array(
                                'id' => 'has_source_discount',
                                'class' => 'checkbox_source_show_input'
                            )
                        ) }} 
                        {{ Form::label('has_source_discount', 'خصم من المنبع') }}
                    </div>
                    <div class="source_hidden_input">

                        <div class="col-lg-5">
                            <div class="form-group{{ $errors->has('source_discount_percentage') ? ' has-error' : '' }}">
                                {{ Form::label('source_discount_percentage', 'نسبة الخصم') }} 
                                <div class="input-group">
                                    {{ Form::text('source_discount_percentage', null, 
                                        array(
                                            'id' => 'source_discount_percentage',
                                            'class' => 'form-control', 
                                            'placeholder' => 'ادخل النسبة'
                                            )
                                        )
                                    }}
                                    <span class="input-group-addon">
                                        %
                                    </span>    
                                </div><!-- /input-group -->
                                @if ($errors->has('source_discount_percentage'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('source_discount_percentage') }}
                                </label>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group{{ $errors->has('source_discount_value') ? ' has-error' : '' }}">
                                {{ Form::label('source_discount_value', 'القيمة') }} 
                                {{ Form::text('source_discount_value', null, 
                                    array(
                                        'class' => 'form-control', 
                                        'placeholder' => 'ادخل القيمة'
                                        )
                                    )
                                }}
                                @if ($errors->has('source_discount_value'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('source_discount_value') }}
                                </label>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="col-lg-12 no-padding">
                    <div class="legend">
                        {{ Form::checkbox('require_invoice', '1', null, 
                            array(
                                'id' => 'require_invoice'
                            )
                        ) }} 
                        {{ Form::label('require_invoice', 'فاتورة') }}
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
@section('scripts')
<script src="{{ mix('js/prcoess_items.js') }}"></script>
@endsection