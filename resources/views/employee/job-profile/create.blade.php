@extends('layouts.app') 
@section('title', 'الوظيفة')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> تعديل الوظيفة</h1>
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

    {{ Form::open(['route' => ['employee.employeeJobProfile.store', $employee->id]]) }}
        @include('employee.job-profile._form')
    {{ Form::close() }}

</div>
@endsection