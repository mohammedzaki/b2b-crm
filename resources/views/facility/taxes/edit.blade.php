@extends('layouts.app') 
@section('title', 'تعديل بند ضريبة') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> <small>تعديل بند ضريبة</small></h1>
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

    {{ Form::model($facilityTaxes, 
            array(
                'route' => array('facilityTaxes.update', $facilityTaxes->id),
                'method' => 'put'
            )
        ) 
    }}
        @if (session('error'))
            @include('facility.taxes._form', ['model' => 'edit'])
        @else
            @include('facility.taxes._form', ['model' => 'edit'])
        @endif
    {{ Form::close() }}

    

</div>
@endsection