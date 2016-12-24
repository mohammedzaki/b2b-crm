@extends('layouts.app') 
@section('title', 'تعديل بيانات عملية - عمليه جديدة عميل') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">عمليه جديدة عميل <small>تعديل بيانات عملية</small></h1>
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

    {{ Form::model($borrow, 
            array(
                'route' => array('employeeBorrow.update', $borrow->id),
                'method' => 'put'
            )
        ) 
    }}
        @if (session('error'))
            @include('employee.borrow._form', ['model' => 'edit', 'items' => old('items')])
        @else
            @include('employee.borrow._form', ['model' => 'edit', 'items' => $borrow->items])
        @endif
    {{ Form::close() }}

    <script type="text/javascript">
        @if (session('error'))
            var processItemsCount = {{ count(old('items')) }};
        @else
            var processItemsCount = {{ count($borrow->items) }};
        @endif
    </script>

</div>
@endsection