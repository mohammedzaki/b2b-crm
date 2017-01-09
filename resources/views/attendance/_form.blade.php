@section('css_scripts')
<link href="{{ url('vendors/flatpickr/dist/flatpickr.min.css') }}" rel="stylesheet">
@endsection

<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات الحضور والانصراف
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">

            <div class="col-lg-6 ">
                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                    {{ Form::label('employee_id', 'اسم الموظف') }} 
                    {{ Form::select('employee_id', $employees, null, 
                                        array(
                                        'class' => 'form-control',
                                        'placeholder' => '')) }}
                    @if ($errors->has('employee_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('employee_id') }}
                    </label>
                    @endif
                </div>
                @if ($checkin)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group{{ $errors->has('process_id') ? ' has-error' : '' }}">
                            {{ Form::label('process_id', 'اسم العملية') }} 
                            <div class="input-group">
                                {{ Form::select('process_id', $processes, null, array(
                                        'class' => 'form-control',
                                        'onchange' => 'ResetManagmentProcess()',
                                        'placeholder' => '')) }}
                                <span class="input-group-addon">
                                    {{ Form::checkbox('is_managment_process', '1', null, array('id' => 'is_managment_process', 'onchange' => 'ResetProcess()')) }} 
                                    {{ Form::label('is_managment_process', 'عمليات ادارية') }}
                                </span>    
                            </div><!-- /input-group -->
                            @if ($errors->has('process_id'))
                            <label for="inputError" class="control-label">
                                {{ $errors->first('process_id') }}
                            </label>
                            @endif
                        </div>
                    </div><!-- /.col-lg-6 -->
                </div>
                @endif
                <div class="form-group">
                    <label>المكافأت</label>
                    {{ Form::text('mokaf', null, array(
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل المكافأت')) }}
                </div>
                <div class="form-group">
                    <label>الخصومات</label>
                    {{ Form::text('salary_deduction', null, array(
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل الخصومات')) }}
                </div>
            </div>
            <div class="col-lg-6 ">
                <div class="form-group">
                    <label>اليوم</label>
                    {{ Form::text('date', null, array(
                                        "id" => "datepicker",
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل اليوم')) }}
                </div>
                <div class="row">
                    @if(isset($model))
                    <div class="form-group col-lg-6">
                        <label>ساعة الدخول</label>
                        {{ Form::text('check_in', null, array(
                                        "id" => "check_in",
                                        'class' => 'form-control',
                                        'placeholder' => 'ساعة الدخول')) }}
                    </div>

                    <div class="form-group col-lg-6">
                        <label>ساعة الخروج</label>
                        {{ Form::text('check_out', null, array(
                                        "id" => "check_out",
                                        'class' => 'form-control',
                                        'placeholder' => 'ساعة الخروج')) }}
                    </div>
                    @else
                    <div class="form-group col-lg-12">
                        <label>الساعة</label>
                        {{ Form::text('check_time', null, array(
                                        "id" => "timepicker",
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل الساعة')) }}
                    </div>
                    @endif

                </div>
                <div class="form-group">
                    <label> ساعات العمل</label>
                    {{ Form::text('workingHours', null, array(
                                        "id" => "workingHours",
                                        'class' => 'form-control',
                                        'disabled' => 'disabled',
                                        'placeholder' => '')) }}
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    {{ Form::text('notes', null, array(
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل الملاحظات')) }}
                </div>
            </div>
            <div class="col-lg-12"> 

                <div class="legend">
                    {{ Form::checkbox('absent_check', '1', null, 
                            array(
                                'id' => 'absent_check',
                                'class' => 'checkbox_show_input'
                            )) }} 
                    {{ Form::label('absent_check', 'غياب') }}
                </div>
                <div class="hidden_input">
                    <div class="row"> 
                        <div class="col-lg-6 ">
                            <div class="form-group">
                                {{ Form::label('absent_type_id', 'نوع الغياب') }}
                                {{ Form::select('absent_type_id', $absentTypes, null, array(
                                        'class' => 'form-control',
                                        'placeholder' => '')) }}
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="form-group">
                                <label>
                                    المبلغ
                                </label>
                                {{ Form::text('absent_deduction', null, array(
                                        'class' => 'form-control',
                                        'placeholder' => '')) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 ">
                            <button class="btn btn-danger" type="button">حفظ</button>
                        </div>
                    </div>
                </div>
            </div>



        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<div class="col-lg-12">
    <div class="col-lg-6">
        <div class="form-group buttonsdiv">
            @if(isset($model))
           
            @if ($checkinbtn)
            <input type="hidden" name="checkin" value="1" />
            <a class="btn btn-primary" href="{{ URL::route('attendance.checkin') }}">جديد</a>
            @else
            <input type="hidden" name="checkin" value="0" />
            <a class="btn btn-primary" href="{{ URL::route('attendance.checkout') }}">جديد</a>
            @endif
            
            <button class="btn btn-success" type="submit">حفظ التعديلات</button>
            @else
                @if ($checkin)
                <button class="btn btn-success" type="button" onclick="SubmitCheck(1)">تسجيل حضور</button>
                @else
                <button class="btn btn-primary" type="button" onclick="SubmitCheck(0)">تسجيل انصراف</button>
                @endif
            @endif
        </div>
    </div>

</div>

@section('scripts')
<script src="{{ url('/vendors/flatpickr/dist/flatpickr.min.js') }}"></script>
<script src="{{ url('/vendors/flatpickr/dist/l10n/ar.js') }}"></script>
<script>
var employeesSalaries = [
    @foreach($employeesSalaries as $k => $info) 
    { id: '{{ $k }}', hourlySalary: '{{ $info["hourlySalary"] }}'}, 
    @endforeach];
var absentTypesInfo = [
    @foreach($absentTypesInfo as $k => $info) 
    { id: '{{ $k }}', salaryDeduction: '{{ $info["salaryDeduction"] }}', editable: '{{ $info["editable"] }}'}, 
    @endforeach];

$(function () {
    //var startDate = new Date();
    //var endDate = new Date();
    var timepicker = $("#timepicker").flatpickr({
        enableTime: true,
        altInput: true,
        maxDate: new Date(),
        defaultDate: new Date(),
        altFormat: "l, j F, Y - h:i K",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            //endDate = selectedDates[0];
            //var hours = Math.abs(endDate - startDate) / 36e5;
            //$('#workinghours').val(hours);
        }
    });
    var datepicker = $("#datepicker").flatpickr({
        enableTime: false,
        maxDate: new Date(),
        defaultDate: new Date(),
        altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            //startDate = selectedDates[0];
            timepicker.setDate(selectedDates[0]);
            timepicker.open();
            //console.log(selectedDates, dateStr, instance);
        }
    });
});
function SubmitCheck(checkType) {
    // 
    if (checkType == 1) {
        $('#attendanceForm').append('<input type="hidden" id="check_in" name="check_in" value="' + $('#timepicker').val() + '"/>')
    } else {
        $('#attendanceForm').append('<input type="hidden" id="check_out" name="check_out" value="' + $('#timepicker').val() + '"/>')
    }
    $('#attendanceForm').submit();
}
function ResetProcess() {
    if ($('#is_managment_process').is(":checked")) {
        $('#process_id').val('');
    }
}
function ResetManagmentProcess() {
    if ($('#process_id').val() > 0) {
        $('#is_managment_process').prop('checked', false);
    }
}
</script>
@endsection