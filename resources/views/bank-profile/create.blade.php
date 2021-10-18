@extends('layouts.app') 
@section('title', 'اضف بنك جديد - ادارة البنكوك')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ادارة البنكوك <small>أضف جديد</small></h1>
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

    {{ Form::open(['route' => 'bank-profile.store']) }}
        @include('bank-profile._form')
    {{ Form::close() }}

</div>
@endsection