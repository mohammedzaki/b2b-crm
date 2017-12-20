
{{ Form::open(['method' => 'GET', 'route' => ['salary.show', ''], 'id' => 'SearchForm']) }}
    <row>
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
                            <label>اليوم</label>
                            {{ Form::text('date', $date, array(
                                        "id" => "datepicker",
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل اليوم')) }}
                        </div>
                    </div>
                    <div class="col-lg-2 ">
                        <label>></label>
                        <button class="btn btn-success form-control" type="button" onclick="SubmitSearch()">بحث</button>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </row>
{{ Form::close() }}

@section('scripts')
<script>
    var datepicker = $("#datepicker").flatpickr({
        enableTime: false,
        altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar"
    });
    function SubmitSearch() {
        var empId = $("#employee").val();
        var action = $('#SearchForm').prop('action') + '/' + empId;
        $('#SearchForm').prop('action', action).submit();
    }
</script>
@endsection