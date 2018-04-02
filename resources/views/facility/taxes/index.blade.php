@extends('layouts.app') 
@section('title', 'عرض الكل - الضريبة')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> الضريبة<small>عرض الكل</small></h1>
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
                                    <th>تاريخ التعديل</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>النسبة</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($facilityTaxes as $item)
                                <tr role="row">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->changedate }}</td>
                                    <td>{{ $item->enddate }}</td>
                                    <td>{{ $item->percentage }}</td>
                                    <td>
                                        {{ link_to_route('facilityTaxes.edit', 'تعديل', array('id' => $item->id), array('class' => 'btn btn-primary')) }}
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
                    
                    <!-- Date Picker-->
                    {{ Form::open(['route' => 'facilityTaxes.create', 'method' => 'get']) }}
                    
                    <button type="submit" class="btn btn-success">تعديل الضريبة</button>
                    {{ Form::close() }}
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
        return confirm("هل انت متأكد من حذف الرصيد ؟");
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