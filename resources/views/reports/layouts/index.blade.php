@extends('source-code.b2b-crm.resources.views.layouts.app')
@section('title', 'تقرير عملية عميل - التقارير')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">التقارير </h1>
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

    <div class="col-lg-6">
        
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection
