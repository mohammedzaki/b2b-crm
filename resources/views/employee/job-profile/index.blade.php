@extends('layouts.app') 
@section('title', 'عرض الكل - راتب الموظف')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> راتب الموظف<small>عرض الكل</small></h1>
    </div>
</div>
<!-- /.row -->

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
                الارصدة
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>مسلسل</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>المسمى الوظيفى</th>
                                    <th>عدد الساعات</th>
                                    <th>الراتب اليومى</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employeeJobProfiles as $item)
                                <tr role="row">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->start_date }}</td>
                                    <td>{{ $item->end_date }}</td>
                                    <td>{{ $item->job_title }}</td>
                                    <td>{{ $item->working_hours }}</td>
                                    <td>{{ $item->daily_salary }}</td>
                                    <td>
                                        {{ link_to_route('employee.employeeJobProfile.edit', 'تعديل', ['id' => $item->id, 'employee' => $item->employee_id], array('class' => 'btn btn-primary')) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>ﻻ يوجد ارصدة.</tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <div class="col-md-6 text-left">
                    {{ link_to_route('employee.employeeJobProfile.create', 'تعديل الوظيفة', ['employee' => $item->employee_id], array('class' => 'btn btn-success')) }}
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
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true,
            language: {
                url: "http://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json"
            }
        });
    });
</script>
@endsection