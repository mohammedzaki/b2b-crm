@extends('layouts.app') 
@section('title', 'تعديل بيانات مورد - اضف مورد') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة مورد <small>تعديل بيانات مورد</small></h1>
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

    {{ Form::model($supplier, 
            array(
                'route' => array('supplier.update', $supplier->id),
                'method' => 'put'
            )
        ) 
    }}
        @include('supplier._form', ['model' => 'edit'])
    {{ Form::close() }}

</div>
@endsection