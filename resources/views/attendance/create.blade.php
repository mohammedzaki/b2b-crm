@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if ($checkin)
        <h1 class="page-header">شاشة حضور الموظفين</h1>
        @else 
        <h1 class="page-header">شاشة انصراف الموظفين</h1>
        @endif
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
