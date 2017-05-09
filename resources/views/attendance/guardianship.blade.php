@extends('layouts.app')

@section('title', 'كشف حساب العهد')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">كشف حساب العهد</h1>
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
</form>
<row class="col-lg-12 clearboth">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    كشف حساب العهد
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">

                        <div class="table-responsive">
                            <table width="2000" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>م</th>
                                        <th>عهدة</th>
                                        <th>رد عهدة</th>
                                        <th>بيان</th>
                                        <th>التاريخ</th>
                                        <th>عهدة مرحلة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employeeGuardianships as $index => $guardianship)
                                    <tr class="odd">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $guardianship->withdrawValue }}</td>
                                        <td>{{ $guardianship->depositValue }}</td>
                                        <td>{{ $guardianship->recordDesc }}</td>
                                        <td>{{ $guardianship->due_date }}</td>
                                        <td>{{ $guardianship->notes }}</td>
                                    </tr>
                                    @empty
                                    <tr>ﻻ يوجد بيانات.</tr>
                                    @endforelse
                                    <tr>
                                        <th></th>
                                        <th>{{ $totalGuardianshipValue }}</th>
                                        <th>{{ $totalGuardianshipReturnValue }}</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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