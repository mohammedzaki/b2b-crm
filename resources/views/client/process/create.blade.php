@extends('layouts.app') 
@section('title', 'اضف عملية جديدة - عمليه جديدة عميل') 

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">عمليه جديدة عميل <small>أضف جديد</small></h1>
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

    {{ Form::open(['route' => 'client.process.store']) }}
        @include('client.process._form', ['items' => old('items')])
    {{ Form::close() }}

    <script type="text/javascript">
        var processItemsCount = 0;
    </script>

</div>
@endsection