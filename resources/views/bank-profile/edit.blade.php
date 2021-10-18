@extends('layouts.app') 
@section('title', 'تعديل بيانات بنك - ادارة البنكوك')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ادارة البنكوك <small>تعديل بيانات بنك</small></h1>
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

    {{ Form::model($bankProfiles,
            array(
                'route' => array('bank-profile.update', $bankProfiles->id),
                'method' => 'put'
            )
        ) 
    }}
        @include('bank-profile._form', ['model' => 'edit'])
    {{ Form::close() }}

</div>
@endsection