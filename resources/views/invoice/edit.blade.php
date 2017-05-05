@extends('layouts.app') 
@section('title', 'تعديل بيانات فاتورة - الفاتورة') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">الفاتورة <small>تعديل بيانات فاتورة</small></h1>
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

    {{ Form::model($invoice, array(
                'route' => array('invoice.update', $invoice->id),
                'method' => 'put', 'id' => 'invoiceForm')) 
    }}
        @include('invoice._form', ['model' => 'edit', 'invoiceItems' => $invoice->items])
    {{ Form::close() }}

</div>
@endsection