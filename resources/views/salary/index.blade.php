@extends('layouts.app')

@section('title', 'مرتبات الموظفين')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">مرتبات الموظفين</h1>
    </div>
</div>
@include('salary.select-employee')
@endsection
