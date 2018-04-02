@extends('layouts.app') 
@section('title', 'الضريبة')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> تعديل الضريبة</h1>
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

    {{ Form::open(['route' => 'facilityTaxes.store']) }}
        @include('facility.taxes._form')
    {{ Form::close() }}

</div>
@endsection