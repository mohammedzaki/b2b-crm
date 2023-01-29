<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الرصيد
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                    {{ Form::label('reason', 'سبب الرصيد') }}
                    {{ Form::text('reason', null,
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل سبب الرصيد')
                        )
                    }}
                    @if ($errors->has('reason'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('reason') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                    {{ Form::label('amount', 'القيمة') }}
                    {{ Form::text('amount', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل قيمة الرصيد')
                        )
                    }}
                    @if ($errors->has('amount'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('amount') }}
                    </label>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('save_id') ? ' has-error' : '' }}">
                    {{ Form::label('save_id', 'اسم الحساب') }}
                    {{ Form::select('save_id', $allBanks, isset($model) ? $openingAmount->save_id : null,
                            array(
                                'class' => 'form-control',
                                'id' => 'save_id',
                                'name' => 'save_id',
                                'placeholder' => 'اختر الحساب')
                            )
                    }}
                    @if ($errors->has('save_id'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('save_id') }}
                        </label>
                    @endif
                </div>
                <div class="form-group">
                    {{ Form::label('deposit_date', 'التاريخ') }}
                    {{ Form::text('deposit_date', null, array(
                                        "id" => "deposit_date",
                                        'readonly' => 'true',
                                        'class' => 'form-control datepickerCommon',
                                        'placeholder' => 'ادخل التاريخ')) }}
                </div>
            </div>
            <!-- /.panel-body -->
        </div>

        <div style="margin-bottom: 20px;">
            <button class="btn btn-lg btn-block btn-success" type="submit">
                @if(isset($model))
                تعديل الرصيد
                @else
                أضف رصيد جديد
                @endif
            </button>
        </div>
    </div>

</div>