@extends('layouts.app') 
@section('title', 'عرض الكل - اضف مصروف')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة مصروف <small>عرض الكل</small></h1>
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
	           المصروفات
	        </div>
	        <!-- /.panel-heading -->
	        <div class="panel-body">
	            <div class="dataTable_wrapper">
	                <div class="table-responsive">
	                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
	                        <thead>
	                            <tr>
	                                <th width="40">الرقم</th>
	                                <th>اسم المصروف</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@forelse ($expenses as $expense)
	                        	<tr role="row">
	                        	    <td class="text-center">{{ $expense->id }}</td>
	                        	    <td>{{ $expense->name }}</td>
	                        	    <td>
	                        	        {{ Form::open(['method' => 'DELETE', 'route' => ['expenses.destroy', $expense->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
	                        	            {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
	                        	        {{ Form::close() }}

	                        	        {{ link_to_route('expenses.edit', 'تعديل', array('id' => $expense->id), array('class' => 'btn btn-primary')) }}
	                        	    </td>
	                        	</tr>
	                        	@empty
                                    <tr>ﻻ يوجد مصروفات.</tr>
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
    return confirm("هل انت متأكد من حذف المصروف ؟");
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