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
                                        {{--<th>#</th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employeeGuardianships as $guardianship)
                                    <tr class="odd">
                                        <td>1</td>
                                        <td>
                                            @if($guardianship->withdrawValue > 0)
                                                {{ $guardianship->withdrawValue }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($guardianship->depositValue > 0)
                                                {{ $guardianship->depositValue }}
                                            @endif
                                        </td>
                                        <td>{{ $guardianship->recordDesc }}</td>
                                        <td>{{ $guardianship->created_at }}</td>
                                        {{--<td>
                                            
                                            @if($guardianship->withdrawValue > 0)
                                            {{ link_to_route('attendance.guardianshipaway', 'ترحيل العهدة', array('id' => 1), array('class' => 'btn btn-primary')) }}
                                            @endif
                                            
                                        </td>--}}
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
                                        {{--<th></th>--}}
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