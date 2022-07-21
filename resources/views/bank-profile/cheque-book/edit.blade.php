@extends('layouts.app') 
@section('title', 'تعديل الدفتر')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> <small>تعديل الدفتر</small></h1>
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

    {{ Form::model($bankChequeBook,
            array(
                'route' => array('bank-profile.cheque-book.update', $bankProfile->id, $bankChequeBook->id),
                'method' => 'put'
            )
        ) 
    }}
        @include('bank-profile.cheque-book._form', ['model' => 'edit'])
    {{ Form::close() }}

    

</div>
@endsection