@extends('layouts.app') 
@section('title', 'تعديل بيانات موظف - صﻻحيات الموظفين') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">صلاحيات الموظفين <small>تعديل بيانات موظف</small></h1>
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

    {{ Form::model($employee, 
            array(
                'route' => array('employee.update', $employee->user_id),
                'method' => 'put'
            )
        ) 
    }}
    @include('employee._form', ['model' => 'edit'])
    {{ Form::close() }}
</div>
<!-- /.row -->
@endsection