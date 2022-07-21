<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الوظيفة
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                    {{ Form::label('job_title', 'المسمى الوظيفى') }}
                    {{ Form::text('job_title', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل المسمى الوظيفى')
                        )
                    }}
                    @if ($errors->has('job_title'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('job_title') }}
                    </label>
                    @endif
                </div>
                <div class="form-group">
                    {{ Form::label('start_date', 'تاريخ البداية') }}
                    {{ Form::text('start_date', null, array(
                                'class' => 'form-control'))
                    }}
                </div>
                <!--<div class="form-group">
                    {{ Form::label('end_date', 'تاريخ الانتهاء') }}
                    {{ Form::text('end_date', null, array(
                                'class' => 'form-control'))
                    }}
                </div>-->
                <div class="form-group{{ $errors->has('working_hours') ? ' has-error' : '' }}">
                    {{ Form::label('working_hours', 'عدد ساعات العمل') }}
                    {{ Form::text('working_hours', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل عدد ساعات العمل')
                        )
                    }}
                    @if ($errors->has('working_hours'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('working_hours') }}
                    </label>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('daily_salary') ? ' has-error' : '' }}">
                    {{ Form::label('daily_salary', 'الراتب اليومى') }}
                    {{ Form::text('daily_salary', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل الراتب اليومى')
                        )
                    }}
                    @if ($errors->has('daily_salary'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('daily_salary') }}
                    </label>
                    @endif
                </div>
            </div>
            <!-- /.panel-body -->
        </div>

        <div style="margin-bottom: 20px;">
            <button class="btn btn-lg btn-block btn-success" type="submit">
                @if(isset($model))
                تعديل الوظيفة
                @else
                أضف الوظيفة الجديدة
                @endif
            </button>
        </div>
    </div>

</div>

@section('scripts')
<script>
    $(function () {
      $("#start_date").flatpickr({
        enableTime: false,
        //maxDate: new Date(),
        altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar"
      });
    });
</script>
@endsection