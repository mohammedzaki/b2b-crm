<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الدفتر
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'اسم الدفتر') }}
                    {{ Form::text('name', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'اسم الدفتر')
                        )
                    }}
                    @if ($errors->has('name'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('name') }}
                    </label>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('start_number') ? ' has-error' : '' }}">
                    {{ Form::label('start_number', 'بداية الرقم التسلسلي') }}
                    {{ Form::text('start_number', null, array(
                                'class' => 'form-control'))
                    }}
                    @if ($errors->has('start_number'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('start_number') }}
                        </label>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('end_number') ? ' has-error' : '' }}">
                    {{ Form::label('end_number', 'نهاية الرقم التسلسلي') }}
                    {{ Form::text('end_number', null, array(
                                'class' => 'form-control'))
                    }}
                    @if ($errors->has('end_number'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('end_number') }}
                        </label>
                    @endif
                </div>
            </div>
            <!-- /.panel-body -->
        </div>

        <div style="margin-bottom: 20px;">
            <button class="btn btn-lg btn-block btn-success" type="submit">
                @if(isset($model))
                تعديل الدفتر
                @else
                أضف دفتر جديد
                @endif
            </button>
        </div>
    </div>

</div>