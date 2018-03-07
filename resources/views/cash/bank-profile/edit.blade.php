@extends('layouts.app') 
@section('title', 'تعديل بيانات عميل - اضف عميل') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">اضافة عميل <small>تعديل بيانات عميل</small></h1>
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

    {{ Form::model($client, 
            array(
                'route' => array('client.update', $client->id),
                'method' => 'put'
            )
        ) 
    }}
        @if (session('error'))
            @include('client._form', ['model' => 'edit', 'authorized' => old('authorized')])
        @else
            @include('client._form', ['model' => 'edit'])
        @endif
    {{ Form::close() }}

    <script type="text/javascript">
        var authorizedPeopleCount = {{ count($authorized) }};
    </script>

</div>
@endsection