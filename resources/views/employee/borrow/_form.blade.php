<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات العملية
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                    @if(count($employees) > 1)
                        {{ Form::label('user_id', 'اسم الموظف') }}
                        {{ Form::select('user_id', $employees, null,
                            array(
                                'class' => 'form-control',
                                'id' => 'user_id',
                                'placeholder' => 'ادخل اسم الموظف')
                            )
                        }}
                        @else
                        {{ Form::label('user_id', 'اسم الموظف') }}
                        {{ Form::select('user_id', $employees, $employees[$process->user_id],
                            array(
                                'class' => 'form-control',
                                'id' => 'user_id',
                               )
                            )
                        }}
                        @endif

                    @if ($errors->has('user_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('user_id') }}
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

                <div class="form-group{{ $errors->has('Amount') ? ' has-error' : '' }}">
                    {{ Form::label('Amount', 'القيمة') }}
                    {{ Form::text('Amount', null,
                        array(
                            'class' => 'form-control',
                            'id' => 'Amount',
                            'placeholder' => 'ادخل قيمة السلفية')
                        )
                    }}
                    @if ($errors->has('Amount'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('Amount') }}
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
    {{--<pre>
        {{ var_dump($employees_salary) }}
    </pre>--}}
</div>

<script>
var employeeID, pay_percentage, pay_amount, after_pay, employeeSalary, borrowAmount, salary;
    <?php
        function js_str($s)
        {
            return '"' . addcslashes($s, "\0..\37\"\\") . '"';
        }
        function js_array($array)
        {
            $temp = array_map('js_str', $array);
            return '[' . implode(',', $temp) . ']';
        }

        echo 'employeeSalary = ', js_array($employees_salary), ';';
    ?>

    if(employeeSalary.length == 1){
        salary = employeeSalary[0];
        console.log(salary);
        $('.process_salary').text(salary);
    }


    function calcAfterSalary() {
        $("#after_salary").text(salary - pay_amount);
    }

    $('#has_discount').on('change', function () {
        if(document.getElementById('has_discount').checked) {
            console.log("something");
            $('.final_amount').parent().removeClass('hidden');
        }else {
            $('.final_amount').parent().addClass('hidden');
        }
    });

    $('#Amount').on('keyup', function () {
       if(document.getElementById('has_discount').checked){

            calcAfterSalary();
       }
    });

    $('#pay_percentage').on('keyup', function () {
        pay_percentage = $(this).val();
        console.log(pay_percentage);
        if(pay_percentage == 0 || isNaN(pay_percentage)){
            pay_percentage = pay_amount = 0;
        }else {
            borrowAmount = $('#Amount').val();
            pay_amount = (pay_percentage * borrowAmount) / 100;
        }
        $('#pay_amount').val(pay_amount);

        calcAfterSalary();
    });


    $('#pay_amount').on('keyup', function () {
        pay_amount = $(this).val();
        if(pay_amount == 0 || isNaN(pay_amount)){
            pay_percentage = pay_amount = 0;
        }else {
            borrowAmount = $('#Amount').val();
            pay_percentage = (pay_amount/borrowAmount) * 100;
        }
        $('#pay_percentage').val(pay_percentage);

        calcAfterSalary();
    });

console.log(employeeSalary);
    $('#user_id').on('change', function () {
        employeeID = $(this).val();
        console.log(employeeID);
        salary = employeeSalary[employeeID];
        $('.process_salary').text(employeeSalary[employeeID]);
    });
</script>