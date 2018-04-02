<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الرصيد
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group{{ $errors->has('percentage') ? ' has-error' : '' }}">
                    {{ Form::label('percentage', 'النسبة') }}
                    {{ Form::text('percentage', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل نسبة الرصيد')
                        )
                    }}
                    @if ($errors->has('percentage'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('percentage') }}
                    </label>
                    @endif
                </div>
                <div class="form-group">
                    {{ Form::label('changedate', 'تاريخ التعديل') }}
                    {{ Form::text('changedate', null, array(
                                'class' => 'form-control datepickerCommon'))
                    }}
                </div>
                <div class="form-group">
                    {{ Form::label('enddate', 'تاريخ الانتهاء') }}
                    {{ Form::text('enddate', null, array(
                                'class' => 'form-control datepickerCommon'))
                    }}
                </div>
            </div>
            <!-- /.panel-body -->
        </div>

        <div style="margin-bottom: 20px;">
            <button class="btn btn-lg btn-block btn-success" type="submit">
                @if(isset($model))
                تعديل الضريبة
                @else
                أضف الضريبة الجديدة
                @endif
            </button>
        </div>
    </div>

</div>

@section('scripts')
<script>
    $(function () {
        //$("#changedate").datepicker();
    });
</script>
@endsection