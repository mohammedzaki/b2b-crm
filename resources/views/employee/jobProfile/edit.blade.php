@extends('layouts.app') 
@section('title', 'تعديل بند الوظيفة') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> <small>تعديل بند الوظيفة</small></h1>
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

    {{ Form::model($employeeJobProfile, 
            array(
                'route' => array('employee.employeeJobProfile.update', $employee->id, $employeeJobProfile->id),
                'method' => 'put'
            )
        ) 
    }}
        @if (session('error'))
            @include('employee.jobProfile._form', ['model' => 'edit'])
        @else
            @include('employee.jobProfile._form', ['model' => 'edit'])
        @endif
    {{ Form::close() }}

    

</div>
@endsection