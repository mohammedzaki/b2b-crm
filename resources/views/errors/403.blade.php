@extends('layouts.app')

@section('title', 'خطأ')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">غير مصرح</h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="alert alert-danger">
        ليس لديك صلاحية للدخول الي هذة الصفحة.
    </div>
    <div class="alert alert-info">
        {{ link_to('/', 'اضغط هنا للرجوع الي الرئيسية') }}
    </div>
</div>
@endsection