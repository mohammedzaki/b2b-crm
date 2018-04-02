@extends('layouts.app') 
@section('title', 'اضف مورد جديد - اضف مورد') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة مورد <small>أضف جديد</small></h1>
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

    {{ Form::open(['route' => 'supplier.store']) }}
        @include('supplier._form')
    {{ Form::close() }}

</div>
@endsection