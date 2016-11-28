<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات العملية
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                    {{ Form::label('employee_id', 'اسم الموظف') }}
                    {{ Form::select('employee_id', $employees, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم الموظف')
                        )
                    }}
                    @if ($errors->has('employee_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('employee_id') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('borrow_reason') ? ' has-error' : '' }}">
                    {{ Form::label('borrow_reason', 'سبب السلفية') }}
                    {{ Form::text('borrow_reason', null,
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل سبب السلفية')
                        )
                    }}
                    @if ($errors->has('borrow_reason'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('borrow_reason') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                    {{ Form::label('amount', 'القيمة') }}
                    {{ Form::text('amount', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل قيمة السلفية')
                        )
                    }}
                    @if ($errors->has('amount'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('amount') }}
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
                            <div class="form-group{{ $errors->has('pay_percentage') ? ' has-error' : '' }}">
                                {{ Form::label('pay_percentage', 'نسبة الخصم') }}
                                {{ Form::text('pay_percentage', null,
                                    array(
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل النسبة'
                                        )
                                    )
                                }}
                                @if ($errors->has('pay_percentage'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('pay_percentage') }}
                                </label>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group{{ $errors->has('pay_amount') ? ' has-error' : '' }}">
                                {{ Form::label('pay_amount', 'قيمة الخصم') }}
                                {{ Form::text('pay_amount', null,
                                    array(
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل قيمة الخصم')
                                    )
                                }}
                                @if ($errors->has('pay_amount'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('pay_amount') }}
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>
                    <span>المرتب </span>
                    <span class="price process_price">0</span>
                </h4>

                <hr>
                <h4>
                    <span>بعد الخصم </span>
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

</div>

<script>
var employeeID, pay_percentage, pay_amount, after_pay, employeeSalary;
    
    $('#employee_id').on('change', function () {
        employeeID = 
    });
</script>