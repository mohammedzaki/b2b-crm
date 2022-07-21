@extends('layouts.app')

@section('title', 'سجلات المستخدمين')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">سجلات المستخدم</h1>
        </div>
    </div>
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
                    بيانات المستخدم
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                {{ Form::open(['method' => 'GET', 'route' => ['userLog.search', ''], 'id' => 'SearchForm']) }}
                <!-- Equivalent to... -->
                    <div class="col-lg-4 ">
                        <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                            {{ Form::label('id', 'اسم المستخدم') }}

                            {{ Form::select('user', $users, $user_id,
                                        array(
                                        'id' => 'user',
                                        'name' => 'user_id',
                                        'class' => 'form-control',
                                        'placeholder' => '')) }}
                            @if ($errors->has('employee_id'))
                                <label for="inputError" class="control-label">
                                    {{ $errors->first('id') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            <label>تاريخ البدء</label>
                            {{ Form::text('date', $startDate, array(
                                        "id" => "startDatePicker",
                                        'name' => 'startDate',
                                        'class' => 'form-control',
                                        'placeholder' => 'اختر اليوم')) }}
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="form-group">
                            <label>تاريخ الانتهاء</label>
                            {{ Form::text('date', $endDate, array(
                                        "id" => "endDatePicker",
                                        'name' => 'endDate',
                                        'class' => 'form-control',
                                        'placeholder' => 'اختر اليوم')) }}
                        </div>
                    </div>
                    <div class="col-lg-2 ">
                        <label>></label>
                        <button class="btn btn-success form-control" type="submit">بحث</button>
                    </div>

                    {{ Form::close() }}
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    سجلات المستخدم
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">

                        <div class="table-responsive">
                            <table width="2000" class="table table-striped table-bordered table-hover"
                                   id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>اسم المستخدم</th>
                                    <th>اسم الموظف</th>
                                    <th>الاجراء</th>
                                    <th>القسم / الشاشة</th>
                                    <th>بيانات الاجراء</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($userLogs as $userLog)
                                    <tr class="odd">
                                        <td>{{ $userLog['user'] }}</td>
                                        <td>{{ $userLog['employee'] }}</td>
                                        <td>{{ $userLog['action'] }}</td>
                                        <td>{{ $userLog['entity'] }}</td>
                                        <td>
                                            @foreach($userLog['log_data'] as $item)
                                                {{ $item }} ___
                                            @endforeach
                                        </td>
                                        <td>{{ $userLog['created_at'] }}</td>
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

@endsection

@section('scripts')
    <script>
        $("#startDatePicker").flatpickr({
            enableTime: true,
            locale: "ar",
            altInput: true,
            altFormat: "l, j F, Y - h:i K",
            maxDate: new Date()
        });
        $("#endDatePicker").flatpickr({
            enableTime: true,
            locale: "ar",
            altInput: true,
            altFormat: "l, j F, Y - h:i K",
            maxDate: new Date()
        });

        function SubmitSearch() {
            //var userId = $("#user").val();
            //var action = $('#SearchForm').prop('action') + '/' + userId;
            //$('#SearchForm').prop('action', action).submit();
        }
    </script>
@endsection