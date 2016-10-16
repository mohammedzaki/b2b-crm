@extends('layouts.login')
@section('title', 'خطأ') 
@section('content')
    <div class="alert alert-danger">
        ليس لديك صﻻحية للدخول الي هذة الصفحة. {{ link_to('/', 'اضغط هنا للرجوع الي الرئيسية') }}
    </div>
@endsection