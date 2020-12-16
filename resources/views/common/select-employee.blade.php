<div class="row">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الموظف
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {{ Form::open($formConfig) }}

                <div class="col-lg-6 ">
                    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                        {{ Form::label('id', 'اسم الموظف') }}

                        {{ Form::select('employee', $employees, $employee_id,
                                    array(
                                    'id' => 'employee',
                                    'name' => '',
                                    'class' => 'form-control',
                                    'placeholder' => '')) }}
                        @if ($errors->has('employee_id'))
                            <label for="inputError" class="control-label">
                                {{ $errors->first('id') }}
                            </label>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 ">
                    <div class="form-group">
                        <label>الشهر</label>
                        {{ Form::text('date', $date, array(
                                    "id" => "datepicker",
                                    'class' => 'form-control',
                                    'placeholder' => 'اختر الشهر')) }}
                    </div>
                </div>
                <div class="col-lg-2 ">
                    <label>></label>
                    <button class="btn btn-success form-control" type="button" onclick="SubmitSearch()">بحث</button>
                </div>

                {{ Form::close() }}
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>


@section('scripts')
    <script>
        var datepicker = $("#datepicker").flatpickr({
            enableTime: false,
            locale: "ar",
            altInput: true,
            "plugins": [
                new monthSelectPlugin({
                    dateFormat: "d-m-Y", //defaults to "F Y"
                    altFormat: "F, Y"
                })
            ]
        });

        function SubmitSearch() {
            var empId = $("#employee").val();
            var action = $('#SearchForm').prop('action') + '/' + empId;
            $('#SearchForm').prop('action', action).submit();
        }
    </script>
@endsection