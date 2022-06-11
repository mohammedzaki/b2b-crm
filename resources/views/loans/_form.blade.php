<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات القرض
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
        	<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        	    {{ Form::label('name', 'اسم القرض') }}
        	    {{ Form::text('name', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل اسم القرض')
        	        )
        	    }}
        	    @if ($errors->has('name'))
        	    <label for="inputError" class="control-label">
        	        {{ $errors->first('name') }}
        	    </label>
        	    @endif
        	</div>

            <div class="form-group{{ $errors->has('loan_type') ? ' has-error' : '' }}">
                {{ Form::label('loan_type', 'نوع القرض') }}
                {{ Form::select('loan_type', $loanTypes, null,
                    array(
                        'class' => 'form-control',
                        'placeholder' => '')
                    )
                }}

                @if ($errors->has('loan_type'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('loan_type') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('save_id') ? ' has-error' : '' }}">
                {{ Form::label('save_id', 'الجهة المستفيدة') }}
                {{ Form::select('save_id', $saves, null,
                    array(
                        'class' => 'form-control',
                        'placeholder' => '')
                    )
                }}
                @if ($errors->has('save_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('save_id') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                {{ Form::label('date', 'التاريج') }}
                {{ Form::text('date', null,
                    array(
                        'class' => 'form-control datepickerCommon',
                        'placeholder' => 'ادخل التاريج')
                    )
                }}
                @if ($errors->has('date'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('date') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                {{ Form::label('amount', 'قيمة القرض') }}
                {{ Form::text('amount', null,
                    array(
                        'class' => 'form-control IsNumberDecimal',
                        'placeholder' => 'ادخل قيمة القرض')
                    )
                }}
                @if ($errors->has('amount'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('amount') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('interest') ? ' has-error' : '' }}">
                {{ Form::label('interest', 'فايدة القرض') }}
                {{ Form::text('interest', null,
                    array(
                        'class' => 'form-control IsNumberDecimal',
                        'placeholder' => 'ادخل فايدة القرض')
                    )
                }}
                @if ($errors->has('interest'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('interest') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                {{ Form::label('duration', 'عدد السنوات') }}
                {{ Form::text('duration', null,
                    array(
                        'class' => 'form-control IsNumberOnly',
                        'placeholder' => 'ادخل عدد السنوات')
                    )
                }}
                @if ($errors->has('duration'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('duration') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('loan_after_interest') ? ' has-error' : '' }}">
                {{ Form::label('loan_after_interest', 'القيمة بالفائدة') }}
                {{ Form::text('loan_after_interest', null,
                    array(
                        'class' => 'form-control IsNumberDecimal',
                        'readonly' => 'readonly',
                        'placeholder' => '')
                    )
                }}
                @if ($errors->has('loan_after_interest'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('loan_after_interest') }}
                    </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('monthly_installment') ? ' has-error' : '' }}">
                {{ Form::label('monthly_installment', 'القسط الشهرى') }}
                {{ Form::text('monthly_installment', null,
                    array(
                        'class' => 'form-control IsNumberDecimal',
                        'readonly' => 'readonly',
                        'placeholder' => '')
                    )
                }}
                @if ($errors->has('monthly_installment'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('monthly_installment') }}
                    </label>
                @endif
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<row class="col-lg-12" style="padding-bottom: 20px;">
    <div class="col-lg-6">
        <button class="btn btn-lg btn-block btn-success" type="submit">
            @if(isset($model))
                تعديل بيانات قرض
            @else
                أضف قرض جديد
            @endif
        </button>
    </div>
</row>