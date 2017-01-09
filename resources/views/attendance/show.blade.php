@extends('layouts.app')

@section('title', 'حضور وانصراف الموظفين')

@section('css_scripts')
<link href="{{ url('vendors/flatpickr/dist/flatpickr.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">حضور وانصراف الموظفين</h1>
    </div>
</div>
<form method="get" id="SearchForm" action="" >
    <row>
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
                            {{ Form::select('id', $employees, null, 
                                        array(
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
                            {{ Form::text('date', null, array(
                                        "id" => "datepicker",
                                        'class' => 'form-control',
                                        'placeholder' => 'ادخل اليوم')) }}
                        </div>
                    </div>
                    <div class="col-lg-2 ">
                        <label></label>
                        <button class="btn btn-success" type="button" onclick="SubmitSearch()">بحث</button>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </row>
</form>
<row class="col-lg-12 clearboth">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    كشف حساب الموظف
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">

                        <div class="table-responsive">
                            <table width="2000" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>اسم الموظف</th>
                                        <th>اسم العملية</th>
                                        <th>من</th>
                                        <th>الى</th>
                                        <th>ساعات العمل</th>
                                        <th>المكافأت</th>
                                        <th>الخصومات</th>
                                        <th>الملاحظات</th>
                                        <th>السلف</th>
                                        <th>نوع الغياب</th>
                                        <th>خصم الغياب</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendances as $attendance)
                                    <tr class="odd">
                                        <td>{{ $attendance->employeeName }}</td>
                                        <td>{{ $attendance->processName }}</td>
                                        <td>{{ $attendance->check_in }}</td>
                                        <td>{{ $attendance->check_out }}</td>
                                        <td>{{ $attendance->workingHours }}</td>
                                        <td>{{ $attendance->mokaf }}</td>
                                        <td>{{ $attendance->salary_deduction }}</td>
                                        <td>{{ $attendance->notes }}</td>
                                        <td>??????</td>
                                        <td>{{ $attendance->absentTypeName }}</td>
                                        <td>{{ $attendance->absent_deduction }}</td>
                                    </tr>
                                    @empty
                                    <tr>ﻻ يوجد بيانات.</tr>
                                    @endforelse
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>اجمالى</th>
                                        <th>{{ $totalWorkingHours }}</th>
                                        <th>{{ $totalBonuses }}</th>
                                        <th>{{ $totalSalaryDeduction }}</th>
                                        <th></th>
                                        <th>??????</th>
                                        <th></th>
                                        <th>{{ $totalAbsentDeduction }}</th>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>


</row>
<row>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الاجر
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label>عدد الساعات الفعلى</label>
                        {{ Form::text('HourlyRate', $totalWorkingHours, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-4">
                        <label>سعر الساعة</label>
                        {{ Form::text('HourlyRate', $hourlyRate, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-4">
                        <label>الاجمالى</label>
                        {{ Form::text('HourlyRate', ($totalWorkingHours * $hourlyRate), array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label>خصومات الغياب</label>
                        {{ Form::text('HourlyRate', $totalAbsentDeduction, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>السلف المستديمة</label>
                        {{ Form::text('HourlyRate', '??????', array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>سلف يومية</label>
                        {{ Form::text('HourlyRate', '??????', array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>خصم مسبب</label>
                        {{ Form::text('HourlyRate', $totalSalaryDeduction, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label>اجمالى السلف</label>
                        {{ Form::text('HourlyRate', '??????', array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    
                    <div class="form-group col-lg-3">
                        <label>المكافأت</label>
                        {{ Form::text('HourlyRate', $totalBonuses, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>المرتب</label>
                        {{ Form::text('TotalSalary', $totalSalary, array(
                                        "id" => "TotalSalary",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>الصافى المستحق</label>
                        {{ Form::text('HourlyRate', '??????', array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                </div>
                <!--
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <label>سعر الساعة</label>
                        {{ Form::text('HourlyRate', $hourlyRate, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <label>اجمالى المرتب</label>
                        {{ Form::text('TotalSalary', $totalSalary, array(
                                        "id" => "TotalSalary",
                                        'class' => 'form-control')) }}
                    </div>
                </div>-->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</row>
@endsection
@section('scripts')
<script src="{{ url('/vendors/flatpickr/dist/flatpickr.min.js') }}"></script>
<script src="{{ url('/vendors/flatpickr/dist/l10n/ar.js') }}"></script>
<script>
var datepicker = $("#datepicker").flatpickr({
    enableTime: false,
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
function SubmitSearch() {
    var empId = $("#id").val();
    $('#SearchForm').prop('action', empId).submit();
}
</script>
@endsection