<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات العملية
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                @if(count($employees) > 0)
                {{ Form::label('employee_id', 'اسم الموظف') }}
                {{ Form::select('employee_id', $employees, null,
                        array(
                            'class' => 'form-control',
                            'id' => 'employee_id',
                            'placeholder' => 'ادخل اسم الموظف')
                        )
                }}
                @else
                {{ Form::label('employee_id', 'اسم الموظف') }}
                {{ Form::select('employee_id', $employees, $employees[$borrow->employee_id],
                        array(
                            'class' => 'form-control',
                            'id' => 'employee_id',
                           )
                        )
                }}
                @endif

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
                        'id' => 'amount',
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
            <div class="col-lg-12 no-padding">
                <div class="legend">
                    {{ Form::checkbox('start_discount', true, null,
                        array(
                            'id' => 'start_discount',
                            'class' => ''
                        )
                    ) }}
                    {{ Form::label('start_discount', 'تخصم ابتداءا من هذا الشهر') }}
                </div>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>
                <span>مرتب الشهر</span>
                <span class="price process_salary">0</span>
            </h4>

            <hr>
            <h4 class="hidden">
                <span>بعد الخصم </span>
                <span class="price final_amount" id="after_salary">0</span>
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


@section('scripts')
<script type="text/javascript">
    var employeesSalaries = {
        @foreach($employeesSalaries as $k => $info) 
            {{ $k }}: { dailySalary: '{{ $info["dailySalary"] }}'},
        @endforeach
    }
</script>

<script type="text/javascript">

    var employeeID, pay_percentage, pay_amount, after_pay, employeeSalary, borrowamount, salary;

    function calcAfterSalary() {
        $("#after_salary").text(roundDecimals((salary - pay_amount), 3));
    }

    $('#has_discount').on('change', function () {
        if (document.getElementById('has_discount').checked) {
            console.log("something");
            $('.final_amount').parent().removeClass('hidden');
        } else {
            $('.final_amount').parent().addClass('hidden');
        }
    });

    $('#amount').on('keyup', function () {
        if (document.getElementById('has_discount').checked) {
            calcAfterSalary();
        }
    });

    $('#pay_percentage').on('keyup', function () {
        pay_percentage = $(this).val();
        console.log(pay_percentage);
        if (pay_percentage == 0 || isNaN(pay_percentage)) {
            pay_percentage = pay_amount = 0;
        } else {
            borrowamount = $('#amount').val();
            pay_amount = (pay_percentage / 100) * salary;
        }
        pay_amount = roundDecimals(pay_amount, 3);
        $('#pay_amount').val(pay_amount);

        calcAfterSalary();
    });

    $('#pay_amount').on('keyup', function () {
        pay_amount = $(this).val();
        if (pay_amount == 0 || isNaN(pay_amount)) {
            pay_percentage = pay_amount = 0;
        } else {
            borrowamount = $('#amount').val();
            pay_percentage = (pay_amount / borrowamount) * 100;
        }
        pay_percentage = roundDecimals(pay_percentage, 3);
        $('#pay_percentage').val(pay_percentage);

        calcAfterSalary();
    });

    $('#employee_id').on('change', employee_change);
    
    function employee_change() {
        employeeID = $("#employee_id").val();
        console.log(employeeID);
        if (employeeID != '') {
            salary = employeesSalaries[employeeID].dailySalary * 30;
            $('.process_salary').text(salary); 
        }
    }
    @if (isset($model))
        employee_change();
    @endif

</script>
@endsection