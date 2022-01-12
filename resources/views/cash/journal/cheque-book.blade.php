@extends('layouts.app')

@section('title', 'دفتر الشيكات')

@section('form-header', " عرض دفتر شيكات ({$chequeBookName}) - {$bankName}")

@section('content')
    <!-- /.row -->
    @include('cash.journal._bank-cash-form')
@endsection