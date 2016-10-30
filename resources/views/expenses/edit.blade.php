@extends('layouts.app') 
@section('title', 'تعديل بيانات مصروف - اضف مصروف')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة مصروف <small>تعديل بيانات مصروف</small></h1>
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

    {{ Form::model($expenses,
            array(
                'route' => array('expenses.update', $expenses->id),
                'method' => 'put'
            )
        ) 
    }}
        @include('expenses._form', ['model' => 'edit'])
    {{ Form::close() }}

</div>
@endsection