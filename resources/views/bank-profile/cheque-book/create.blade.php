@extends('layouts.app') 
@section('title', 'ادخال دفتر جديد')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> ادخال دفتر جديد</h1>
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

    {{ Form::open(['route' => ['bank-profile.cheque-book.store', $bankProfile->id]]) }}
        @include('bank-profile.cheque-book._form')
    {{ Form::close() }}

</div>
@endsection