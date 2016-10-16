@extends('layouts.app') 
@section('title', 'عرض الكل - عمليه جديدة عميل') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">عمليه جديدة عميل <small>عرض الكل</small></h1>
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
	           العمليات
	        </div>
	        <!-- /.panel-heading -->
	        <div class="panel-body">
	            <div class="dataTable_wrapper">
	                <div class="table-responsive">
	                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
	                        <thead>
	                            <tr>
	                                <th width="40">الرقم</th>
	                                <th>اسم العميلة</th>
	                                <th>اسم العميل</th>
	                                <th>مﻻحظات</th>
	                                <th>مبلغ العملية</th>
	                                <th width="150">تحكم</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@forelse ($processes as $process)
	                        	<tr role="row">
	                        	    <td class="text-center">{{ $process->id }}</td>
	                        	    <td>{{ $process->name }}</td>
	                        	    <td>{{ $process->client->name }}</td>
	                        	    <td>{{ $process->notes }}</td>
	                        	    <td>{{ $process->total_price }}</td>
	                        	    <td>
	                        	    	{{ Form::open(['method' => 'DELETE', 'route' => ['client.process.destroy', $process->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
	                        	    	    {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
	                        	    	{{ Form::close() }}

	                        	    	{{ link_to_route('client.process.edit', 'تعديل', array('id' => $process->id), array('class' => 'btn btn-primary')) }}
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

<script>
function ConfirmDelete() {
    return confirm("هل انت متأكد من حذف العميل ؟");
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