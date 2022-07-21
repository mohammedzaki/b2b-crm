@extends('layouts.app') 
@section('title', 'عرض الكل - ادارة البنكوك')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ادارة البنكوك <small>عرض الكل</small></h1>
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
	           البنكوك
	        </div>
	        <!-- /.panel-heading -->
	        <div class="panel-body">
	            <div class="dataTable_wrapper">
	                <div class="table-responsive">
	                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
	                        <thead>
	                            <tr>
									<th width="5%">الرقم</th>
									<th width="30">اسم البنك</th>
									<th width="30">رقم الحساب</th>
									<th width="50">عنوان الفرع</th>
									<th width="100">تحكم</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@forelse ($bankProfiles as $bankProfile)
	                        	<tr role="row">
									<td class="text-center">{{ $bankProfile->id }}</td>
									<td>{{ $bankProfile->name }}</td>
									<td>{{ $bankProfile->statement_number }}</td>
									<td>{{ $bankProfile->branch_address }}</td>
	                        	    <td>
	                        	        {{ Form::open(['method' => 'DELETE', 'route' => ['bank-profile.destroy', $bankProfile->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
	                        	            {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
	                        	        {{ Form::close() }}

	                        	        {{ link_to_route('bank-profile.edit', 'تعديل', array('id' => $bankProfile->id), array('class' => 'btn btn-primary')) }}

										{{ link_to_route('bank-cash.index', 'وارد/منصرف', array('bankId' => $bankProfile->id), array('class' => 'btn btn-primary')) }}

										{{ link_to_route('bank-cash.depositChequeBook', 'إصدار شيك وارد', array('bankId' => $bankProfile->id), array('class' => 'btn btn-primary')) }}

										{{ link_to_route('bank-cash.withdrawChequeBook', 'إصدار شيك منصرف', array('bankId' => $bankProfile->id), array('class' => 'btn btn-primary')) }}
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
    return confirm("هل انت متأكد من حذف البنك ؟");
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