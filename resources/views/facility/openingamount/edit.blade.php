@extends('layouts.app') 
@section('title', 'تعديل بيانات عملية - عمليه جديدة عميل') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> <small>تعديل بيانات رصيد</small></h1>
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

    {{ Form::model($openingAmount, 
            array(
                'route' => array('facilityopeningamount.update', $openingAmount->id),
                'method' => 'put'
            )
        ) 
    }}
        @if (session('error'))
            @include('facility.openingamount._form', ['model' => 'edit'])
        @else
            @include('facility.openingamount._form', ['model' => 'edit'])
        @endif
    {{ Form::close() }}

    

</div>
@endsection