@extends('layouts.app') 
@section('title', 'عرض الكل - صلاحيات الموظفين') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">صلاحيات الموظفين <small>عرض الكل</small></h1>
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
                بيانات الموظفين
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>رقم الموظف</th>
                                    <th>اسم الموظف</th>
                                    <th>الرقم القومى</th>
                                    <th>الجنس</th>
                                    <th>الحالة الاجتماعية</th>
                                    <th>المحمول</th>
                                    <th width="100">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr role="row">
                                        <td class="text-center">{{ $employee->emp_id }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->ssn }}</td>
                                        <td>
                                            @if($employee->gender == 'm')
                                                ذكر
                                            @else
                                                انثى
                                            @endif
                                        </td>
                                        <td>
                                            @if($employee->martial_status == 'single')
                                                اعزب
                                            @elseif($employee->martial_status == 'married')
                                                متزوج
                                            @elseif($employee->martial_status == 'widowed')
                                                ارمل
                                            @elseif($employee->martial_status == 'divorced')
                                                مطلق
                                            @endif
                                        </td>
                                        <td>{{ $employee->mobile }}</td>
                                        <td>
                                            {{ Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
                                                {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
                                            {{ Form::close() }}

                                            {{ link_to_route('employee.edit', 'تعديل', array('id' => $employee->id), array('class' => 'btn btn-primary')) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>ﻻ يوجد موظفين.</tr>
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
<!-- /.row -->
@endsection

@section('scripts')
<script>
function ConfirmDelete() {
    return confirm("هل انت متأكد من حذف الموظف ؟");
}

$(document).ready(function() {
    $('#dataTables-example').DataTable({
        responsive: true,
        language: {
            url: "http://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json"
        }
    });
});
</script>
@endsection