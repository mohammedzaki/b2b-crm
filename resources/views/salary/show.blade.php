@extends('layouts.app')

@section('title', 'مرتبات الموظفين')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">مرتبات الموظفين</h1>
    </div>
</div>

@include('salary.select-employee')

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
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>اسم الموظف</th>
                                        <th>اسم العملية</th>
                                        <th>اليوم</th>
                                        <th>من</th>
                                        <th>الى</th>
                                        <th>ساعات العمل</th>
                                        <th>المكافأت</th>
                                        <th>الخصومات</th>
                                        <th>الملاحظات</th>
                                        <th>السلف</th>
                                        <th>العهد</th>
                                        <th>العهد المردودة</th>
                                        <th>نوع الغياب</th>
                                        <th>خصم الغياب</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendances as $id => $attendance)
                                    <tr class="odd">
                                        <td>{{ $attendance['employeeName'] }}</td>
                                        <td>{{ $attendance['processName'] }}</td>
                                        <td>{{ $attendance['date'] }}</td>
                                        <td>{{ $attendance['check_in'] }}</td>
                                        <td>{{ $attendance['check_out'] }}</td>
                                        <td>{{ $attendance['workingHours'] }}</td>
                                        <td>{{ $attendance['mokaf'] }}</td>
                                        <td>{{ $attendance['salary_deduction'] }}</td>
                                        <td>{{ $attendance['notes'] }}</td>
                                        <td>{{ $attendance['borrowValue'] }}</td>
                                        <td>{{ $attendance['GuardianshipValue'] }}</td>
                                        <td>{{ $attendance['GuardianshipReturnValue'] }}</td>
                                        <td>{{ $attendance['absentTypeName'] }}</td>
                                        <td>{{ $attendance['absent_deduction'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>ﻻ يوجد بيانات.</tr>
                                    @endforelse
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>اجمالى</th>
                                        <th>{{ $totalWorkingHours }}</th>
                                        <th>{{ $totalBonuses }}</th>
                                        <th>{{ $totalSalaryDeduction }}</th>
                                        <th></th>
                                        <th>{{ $totalBorrowValue }}</th>
                                        <th>{{ $totalGuardianshipValue }}</th>
                                        <th>{{ $totalGuardianshipReturnValue }}</th>
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
                        {{ Form::text('HourlyRate', $totalHoursSalary, array(
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
                        {{ Form::text('HourlyRate', $totalLongBorrowValue, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>سلف يومية</label>
                        {{ Form::text('HourlyRate', $totalSmallBorrowValue, array(
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
                        {{ Form::text('HourlyRate', $totalBorrowValue, array(
                                        "id" => "HourlyRate",
                                        'class' => 'form-control')) }}
                    </div>

                    <div class="form-group col-lg-3">
                        <label>المكافأت</label>
                        {{ Form::text('hourlyRate', $totalBonuses, array(
                                        "id" => "hourlyRate",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-lg-3">
                        <label>المرتب</label>
                        {{ Form::text('totalSalary', $totalSalary, array(
                                        "id" => "totalSalary",
                                        'class' => 'form-control')) }}
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <button class="btn btn-success form-control" type="button" onclick="SubmitSearchPrintReport()">طباعة كشف حساب الموظف</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            <label>اجمالى العهدة</label>
                            {{ Form::text('totalSalary', $totalGuardianshipValue, array(
                                        "id" => "totalGuardianshipValue",
                                        'class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            <label>اجمالى رد العهدة</label>
                            {{ Form::text('totalSalary', $totalGuardianshipReturnValue, array(
                                        "id" => "totalGuardianshipReturnValue",
                                        'class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label>الصافى المستحق</label>
                        {{ Form::text('totalNetSalary', $totalNetSalary, array(
                                        "id" => "totalNetSalary",
                                        'class' => 'form-control')) }}
                    </div>
                    @if ($salaryIsPaid)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <button class="btn btn-danger form-control" type="button" onclick="SubmitPaySalary()">دفع المرتب</button>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            <button class="btn btn-primary form-control" type="button" onclick="SubmitSearchGuardianship()">كشف حساب العهد</button>
                        </div>
                    </div>
                    @if ($salaryIsPaid)
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            {{ link_to_route('salary.guardianshipaway', 'ترحيل العهدة', array('employee_id' => $employee_id, 'date' => $date), array('class' => 'btn btn-primary form-control')) }}
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            {{ link_to_route('salary.guardianshipback', 'الغاء ترحيل العهدة', array('employee_id' => $employee_id, 'date' => $date), array('class' => 'btn btn-primary form-control')) }}
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            {{ link_to_route('salary.longBorrowAway', 'ترحيل السلفة المستديمة', array('employee_id' => $employee_id, 'date' => $date), array('class' => 'btn btn-primary form-control')) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</row>
@endsection
@section('scripts')
<script>
    
    function SubmitSearchGuardianship() {
        var empId = $("#employee_id").val();
        $('#SearchForm').prop('action', 'guardianship/' + empId).submit();
    }
    function SubmitSearchPrintReport() {
        var empId = $("#employee_id").val();
        $('#SearchForm').prop('action', 'printSalaryReport/' + empId).submit();
    }
    function SubmitPaySalary() {
        var empId = $("#employee_id").val();
        /*if (empId == '') {
         return alert('يجب اختيار موظف اولا.');
         }*/
        if (ConfirmPay()) {

            $('#SearchForm').append('<input type="hidden" name="totalNetSalary" value="' + $('#totalNetSalary').val() + '" />');
            $('#SearchForm').prop('method', 'post').prop('action', 'paySalary/' + empId).submit();
        }
    }
    function ConfirmPay() {
        return confirm("هل انت متأكد من دفع المرتب ؟");
    }
</script>
@endsection