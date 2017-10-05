@extends('layouts.app') 
@section('title', 'اضف عميل جديد - اضف عميل') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة عميل <small>أضف جديد</small></h1>
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

    {{ Form::open(['route' => 'client.store']) }}
        @include('client._form', ['authorized' => old('authorized')])
    {{ Form::close() }}

    <script type="text/javascript">
        var authorizedPeopleCount = {{ count(old('authorized')) }};
    </script>

</div>
@endsection