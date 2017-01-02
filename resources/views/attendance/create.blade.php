@extends('layouts.app')

@section('title', 'حضور وانصراف الموظفين')


@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">حضور وانصراف الموظفين</h1>
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
    
    {{ Form::open(['route' => 'attendance.store', 'id' => 'attendanceForm']) }}
        @include('attendance._form')
    {{ Form::close() }}
</div>
<!-- /.row -->
@endsection
