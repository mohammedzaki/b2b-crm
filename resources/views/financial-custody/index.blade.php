@extends('layouts.app')

@section('title', 'كشف حساب العهد')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">كشف حساب العهد</h1>
        </div>
    </div>
    @include('common.select-employee', ['formConfig' => ['method' => 'GET', 'route' => ['financialCustody.employeeCustody', ''], 'id' => 'SearchForm']])
@endsection