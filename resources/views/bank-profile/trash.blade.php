@extends('layouts.app') 
@section('title', 'المحذوفات - ادارة البنكوك')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ادارة البنكوك <small>المحذوفات</small></h1>
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
	           البنوك
	        </div>
	        <!-- /.panel-heading -->
	        <div class="panel-body">
	            <div class="dataTable_wrapper">
	                <div class="table-responsive">
	                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
	                        <thead>
	                            <tr>
	                                <th width="40">الرقم</th>
	                                <th>اسم البنك</th>
	                                <th>رقم الحساب</th>
									<th>عنوان الفرع</th>
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
	                        	        {{ link_to_route('bank-profile.restore', 'استرجاع', array('id' => $bankProfile->id), array('class' => 'btn btn-warning')) }}
	                        	    </td>
	                        	</tr>
	                        	@empty
                                    <tr>ﻻ يوجد بنوك.</tr>
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