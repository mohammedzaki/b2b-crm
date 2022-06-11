@extends('layouts.app') 
@section('title', 'تعديل بيانات مصروف - اضف مصروف')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة قرض <small>تعديل بيانات قرض</small></h1>
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

    {{ Form::model($loans,
            array(
                'route' => array('loans.update', $loans->id),
                'method' => 'put'
            )
        ) 
    }}
        @include('loans._form', ['model' => 'edit'])
    {{ Form::close() }}

</div>
@endsection