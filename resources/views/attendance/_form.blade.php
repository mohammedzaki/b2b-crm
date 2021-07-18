@if($checkin === -1)
@section('title', 'بيانات الحضور و الانصراف')   
@elseif ($checkin)
@section('title', 'حضور الموظفين')
@else 
@section('title', 'انصراف الموظفين')
@endif 

<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            @if($checkin === -1)
            بيانات الحضور و الانصراف
            @elseif ($checkin)
            بيانات الحضور
            @else 
            بيانات الانصراف
            @endif 
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">

            <div class="col-lg-6 ">
                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                    {{ Form::label('employee_id', 'اسم الموظف') }} 
                    {{ Form::select('employee_id', $employees, null, 
                                        array(
                                        'id' => 'employee_id',
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'onchange' => ($checkin == 0 ? 'SetCheckIndatetime()' : '') )) }}
                    @if ($errors->has('employee_id'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('employee_id') }}
                    </label>
                    @endif
                </div>
                @if ($checkin || $checkin === -1)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group{{ $errors->has('process_id') ? ' has-error' : '' }}">
                            {{ Form::label('process_id', 'اسم العملية') }} 
                            <div class="input-group">
                                {{ Form::select('process_id', $processes, null, array(
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'onchange' => 'ResetManagmentProcess()')) }}
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
                <div class="form-group{{ $errors->has('is_second_shift') ? ' has-error' : '' }}">
                    <label>اليوم</label>
                    <div class="input-group">
                        {{ Form::text('date', null, array(
                                        "id" => "datepicker",
                                        'class' => 'form-control datepicker',
                                        'placeholder' => 'ادخل اليوم')) }}
                        <span class="input-group-addon">
                            {{ Form::checkbox('is_second_shift', '1', null, array('id' => 'is_second_shift')) }} 
                            {{ Form::label('is_second_shift', 'وردية ثانية') }}
                        </span>    
                    </div><!-- /input-group -->
                    @if ($errors->has('is_second_shift'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('is_second_shift') }}
                    </label>
                    @endif
                </div>
                <div class="row">
                    @if(!$checkin || isset($model) || $checkin === -1)
                    <div class="form-group col-lg-6">
                        <label>ساعة الدخول</label>
                        @if (!$checkin)
                        {{ Form::text('check_in_display', null, array(
                                        "id" => "check_in",
                                        'class' => 'form-control datepicker',
                                        'placeholder' => 'ساعة الدخول')) }}
                        @else
                        {{ Form::text('check_in', null, array(
                                        "id" => "check_in",
                                        'class' => 'form-control datepicker',
                                        'placeholder' => 'ساعة الدخول')) }}
                        @endif
                    </div>

                    <div class="form-group col-lg-6">
                        <label>ساعة الخروج</label>
                        {{ Form::text('check_out', null, array(
                                        "id" => "check_out",
                                        'class' => 'form-control datepicker',
                                        'placeholder' => 'ساعة الخروج')) }}
                    </div>
                    @else
                    <div class="form-group col-lg-12">
                        <label>الساعة</label>
                        {{ Form::text('check_time', null, array(
                                        "id" => "timepicker",
                                        'class' => 'form-control datepicker',
                                        'placeholder' => 'ادخل الساعة')) }}
                    </div>
                    @endif

                </div>
                @if(!$checkin || isset($model) || $checkin === -1)
                <div class="form-group">
                    <label> ساعات العمل</label>
                    {{ Form::text('working_hours', null, array(
                                        "id" => "working_hours",
                                        'class' => 'form-control',
                                        'disabled' => 'disabled',
                                        'placeholder' => '')) }}
                </div>
                @endif
                <div class="form-group">
                    <label>ملاحظات</label>
                    {{ Form::text('notes', null, array(
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل الملاحظات')) }}
                </div>
            </div>
            <div class="col-lg-12"> 
                
                @if ($checkin || isset($model) || $checkin === -1)
                <div class="legend">
                    @if(isset($model) && $attendance->absent_type_id > 0)
                    {{ Form::checkbox('absent_check', '1', true, 
                            array(
                                'id' => 'absent_check',
                                'class' => 'checkbox_show_input',
                                'onchange' => 'RemoveAbsent()'
                            )) }} 
                    {{ Form::label('absent_check', 'غياب') }}
                    @else
                    {{ Form::checkbox('absent_check', '1', null, 
                            array(
                                'id' => 'absent_check',
                                'class' => 'checkbox_show_input',
                                'onchange' => 'RemoveAbsent()'
                            )) }} 
                    {{ Form::label('absent_check', 'غياب') }}
                    @endif
                </div>
                <div class="hidden_input">
                    <div class="row"> 
                        <div class="col-lg-6 ">
                            <div class="form-group">
                                {{ Form::label('absent_type_id', 'نوع الغياب') }}
                                {{ Form::select('absent_type_id', $absentTypes, null, array(
                                        'id' => 'absent_type_id',
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'onchange' => 'SetAbsentDeduction()')) }}
                                
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="form-group">
                                <label>
                                    المبلغ
                                </label>
                                {{ Form::text('absent_deduction', null, array(
                                        'id' => 'absent_deduction',
                                        'class' => 'form-control',
                                        'placeholder' => '')) }}
                            </div>
                        </div>
                    </div>
                    @if(isset($model) || $checkin === -1)
                    @else
                    <div class="row">
                        <div class="col-lg-6 ">
                            <button class="btn btn-danger" type="button" onclick="SubmitCheck(3)">حفظ</button>
                        </div>
                    </div>
                    @endif
                    @endif
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
            <button class="btn btn-success" type="submit">حفظ التعديلات</button>
            @elseif($checkin === -1)
            <button class="btn btn-success" onclick="SubmitCheck(4)">حفظ التعديلات</button>
            @else
                @if ($checkin)
                <button class="btn btn-success visible_input" type="button" onclick="SubmitCheck(1)">تسجيل حضور</button>
                @else
                <button class="btn btn-primary" type="button" onclick="SubmitCheck(2)">تسجيل انصراف</button>
                @endif
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
var employeesSalaries = {!! $employeesSalaries !!};
var absentTypesInfo = {!! $absentTypesInfo !!};
var startDate = new Date();
var endDate = new Date();
var check_inPickr;
var check_outPickr;
var datepicker;
var timepicker;
var selecteddatepicker;
$(function () {
    @if(!$checkin || isset($model) || $checkin === -1)
    check_inPickr = $('#check_in').flatpickr({
        enableTime: true,
        altInput: true,
        @if(!$checkin) clickOpens: false, @endif
        maxDate: new Date(),
        altFormat: "l, j F, Y - h:i K",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            startDate = selectedDates[0];
            caluclateHours();
            check_outPickr.setDate(selectedDates[0]);
            //check_outPickr.open();
        }
    });
    check_outPickr = $('#check_out').flatpickr({
        enableTime: true,
        altInput: true,
        maxDate: new Date(),
        altFormat: "l, j F, Y - h:i K",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            endDate = selectedDates[0];
            caluclateHours();
        }
    });
    datepicker = $("#datepicker").flatpickr({
        enableTime: false,
        maxDate: new Date(),
        @if(!$checkin) defaultDate: new Date(), @endif
        altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            selecteddatepicker = selectedDates[0];
            check_inPickr.setDate(selectedDates[0]);
            SetCheckIndatetime();
            //check_inPickr.open();
        }
    });
    caluclateHours();
    @else
    timepicker = $("#timepicker").flatpickr({
        enableTime: true,
        altInput: true,
        maxDate: new Date(),
        //defaultDate: new Date(),
        altFormat: "l, j F, Y - h:i K",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            //endDate = selectedDates[0];
            //var hours = Math.abs(endDate - startDate) / 36e5;
            //$('#workinghours').val(hours);
        }
    });
    datepicker = $("#datepicker").flatpickr({
        enableTime: false,
        maxDate: new Date(),
        defaultDate: new Date(),
        altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            selecteddatepicker = selectedDates[0];
            timepicker.setDate(selectedDates[0]);
            //SetCheckIndatetime();
            //timepicker.open();
        }
    }); 
    @endif
    SetAbsentDeduction();
});

function caluclateHours() {
    _startDate = check_inPickr.parseDate($('#check_in').val());
    _endDate = check_inPickr.parseDate($('#check_out').val());

    var seconds = Math.floor((_endDate - (_startDate))/1000);
    var minutes = Math.floor(seconds/60);
    var hours = Math.floor(minutes/60);
    var days = Math.floor(hours/24);

    hours = hours-(days*24);
    minutes = minutes-(days*24*60)-(hours*60);
    seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes*60);
    hours = hours + (days*24);
    //console.log('test: ' + hours + ":" + minutes, _startDate , _endDate);
    hours = isNaN(hours) ? '00' : hours;
    minutes = isNaN(minutes) ? '00' : minutes;

    $('#working_hours').val(hours + ":" + minutes);
}

function SubmitCheck(checkType) {
    if (checkType == 1) {
        $('#attendanceForm').append('<input type="hidden" name="checkin" value="1" /> <input type="hidden" id="check_in" name="check_in" value="' + $('#timepicker').val() + '"/>');
    } else if (checkType == 2) {
        $('#attendanceForm').append('<input type="hidden" name="checkin" value="0" /> <input type="hidden" id="check_out" name="check_out" value="' + $('#check_out').val() + '"/>');
    } else if (checkType == 3) {
        $('#attendanceForm').append('<input type="hidden" name="checkin" value="-1" />');
    } else if (checkType == 4) {
        $('#attendanceForm').append('<input type="hidden" name="checkin" value="0" />');
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
function SetAbsentDeduction() {
    if ($("#absent_type_id").val() != 0 && $("#employee_id").val() != 0) {
        console.log(employeesSalaries[$("#employee_id").val()].dailySalary, absentTypesInfo[$("#absent_type_id").val()].salaryDeduction);
        var s_d = employeesSalaries[$("#employee_id").val()].dailySalary * (absentTypesInfo[$("#absent_type_id").val()].salaryDeduction / 100);
        $("#absent_deduction").val(s_d);
        if (absentTypesInfo[$("#absent_type_id").val()].editable == '0') {
            $("#absent_deduction").prop('readonly', true);
        } else {
            $("#absent_deduction").prop('readonly', false);
        }
    } else {
        $("#absent_deduction").val('');
    }
}
function RemoveAbsent() {
    if ($('#absent_check').is(":checked")) {
        $("#absent_type_id").val('');
        $("#absent_deduction").val('');
    }
}
function SetCheckIndatetime() {
    //check_inPickr.setDate(employeesCheckinDates[$("#employee_id").val()].checkinDate);
    selecteddatepicker = $("#datepicker").val();
    check_inPickr.setDate("", true);
    $.get("{{ url('attendance/getEmployeesCheckinDate/') }}", 
        {employee_id: $("#employee_id").val(), date: selecteddatepicker, is_second_shift: $("#is_second_shift").is(":checked")},
        function (data) {
            console.log(data);
            check_inPickr.setDate(data, true);
        });
}
</script>
@endsection