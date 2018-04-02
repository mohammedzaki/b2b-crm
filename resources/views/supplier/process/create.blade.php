@extends('layouts.app') 
@section('title', 'اضف عملية جديدة - عمليه جديدة مورد') 

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">عمليه جديدة مورد <small>أضف جديد</small></h1>
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

    {{ Form::open(['route' => 'supplier.process.store']) }}
        @include('supplier.process._form', ['items' => old('items')])
    {{ Form::close() }}

    <script type="text/javascript">
        var processItemsCount = {{ count(old('items')) }};
    </script>

</div>
@endsection