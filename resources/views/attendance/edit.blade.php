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
    
    {{ Form::model($attendance, array(
                'route' => array('attendance.update', $attendance->id),
                'method' => 'put')) }}
        
        @include('attendance._form', ['model' => 'edit'])
       
    {{ Form::close() }}
</div>
<!-- /.row -->
@endsection
