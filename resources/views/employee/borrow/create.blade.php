@extends('layouts.app') 
@section('title', 'اضف سلفية جديدة')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> سلفية جديدة <small>أضف جديد</small></h1>
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

    {{ Form::open(['route' => 'employeeBorrow.store']) }}
        @include('employee.borrow._form', ['items' => old('items')])
    {{ Form::close() }}
</div>
@endsection