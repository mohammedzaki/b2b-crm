@extends('layouts.app') 
@section('title', 'عرض الكل - اضف مورد') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة مورد <small>عرض الكل</small></h1>
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
                الموردين
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th width="40">الرقم</th>
                                    <th>اسم المورد</th>
                                    <th>العنوان</th>
                                    <th>حد المديونية</th>
                                    <th width="60">التليفون</th>
                                    <th width="60">المحمول</th>
                                    <th width="100">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($suppliers as $supplier)
                                <tr role="row">
                                    <td class="text-center">{{ $supplier->id }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{{ $supplier->credit_limit }}</td>
                                    <td>{{ $supplier->telephone }}</td>
                                    <td>{{ $supplier->mobile }}</td>
                                    <td>
                                        {{ Form::open(['method' => 'DELETE', 'route' => ['supplier.destroy', $supplier->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
                                        {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
                                        {{ Form::close() }}

                                        {{ link_to_route('supplier.edit', 'تعديل', array('id' => $supplier->id), array('class' => 'btn btn-primary')) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>ﻻ يوجد موردين.</tr>
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
    function ConfirmDelete() {
        return confirm("هل انت متأكد من حذف المورد ؟");
    }

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