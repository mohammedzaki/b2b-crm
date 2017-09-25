@extends('layouts.app')

@section('title', 'حضور وانصراف الموظفين')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">حضور وانصراف الموظفين</h1>
    </div>
</div>
<form method="get" id="SearchForm" action="attendance" >
    {{ csrf_field() }}
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
                            {{ Form::hidden('employee_id', $employee_id, array('id' => 'employee_id', 'name' => '')) }}
                            {{ Form::select('id', $employees, $employee_id, 
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
                            {{ Form::text('targetdate', $date, array(
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
</form>

<row class="col-lg-12 clearboth">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    بيانات الموظفين
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">

                        <div class="table-responsive">
                            <table width="2000" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th width="120">اسم الموظف</th>
                                        <th width="120">اسم العملية</th>
                                        <th width="45">اليوم</th>
                                        <th width="45">من</th>
                                        <th width="55">الى</th>
                                        <th width="50">ساعات العمل</th>
                                        <th width="50">المكافأت</th>
                                        <th width="50">الخصومات</th>
                                        <th width="150">الملاحظات</th>
                                        <th width="55">نوع الغياب</th>
                                        <th width="40">خصم الغياب</th>
                                        <th width="40">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendances as $attendance)
                                    <tr class="odd">
                                        <td>{{ $attendance->employeeName }}</td>
                                        <td>{{ $attendance->processName }}</td>
                                        <td>{{ $attendance->date }}</td>
                                        <td>{{ $attendance->check_in }}</td>
                                        <td>{{ $attendance->check_out }}</td>
                                        <td>{{ $attendance->workingHours }}</td>
                                        <td>{{ $attendance->mokaf }}</td>
                                        <td>{{ $attendance->salary_deduction }}</td>
                                        <td>{{ $attendance->notes }}</td>
                                        <td>{{ $attendance->absentTypeName }}</td>
                                        <td>{{ $attendance->absent_deduction }}</td>
                                        <td>
                                            {{ link_to_route('attendance.edit', 'تعديل', array('id' => $attendance->id), array('class' => 'btn btn-primary')) }}
                                            {{ Form::open(['method' => 'DELETE', 'route' => ['attendance.destroy', $attendance->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
                                        {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
                                        {{ Form::close() }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>ﻻ يوجد عمليات.</tr>
                                    @endforelse
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
@endsection
@section('scripts')
<script>
    var datepicker = $("#datepicker").flatpickr({
        enableTime: false,
        altInput: true,
        altFormat: "l, j F, Y",
        locale: "ar",
        onChange: function (selectedDates, dateStr, instance) {
            //startDate = selectedDates[0];
            //timepicker.setDate(selectedDates[0]);
            //timepicker.open();
            //console.log(selectedDates, dateStr, instance);
        }
    });
    function ConfirmDelete() {
        return confirm("هل انت متأكد من حذف السجل ؟");
    }
    function SubmitSearch() {
        var empId = $("#id").val();
        if (empId != '') {
            $('#SearchForm').prop('action', "/attendance/search/" + empId).submit();
        }
    }
</script>
@endsection