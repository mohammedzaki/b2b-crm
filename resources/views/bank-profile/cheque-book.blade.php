@extends('layouts.app') 
@section('title', 'تعديل بيانات بنك - ادارة البنكوك')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ادارة البنكوك <small>دفتر الشيكات</small></h1>
    </div>
</div>

@include('common.select-bank', ['formConfig' => ['method' => 'GET', 'route' => 'bank-profile.chequeBook', 'id' => 'SearchForm']])

<!-- /.row -->
@if(!empty($bankId))
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
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                دفتر الشيكات
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body operationdes">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>اسم الدفتر</th>
                            <th>الرقم التسلسلي من</th>
                            <th>الرقم التسلسلي الي</th>
                            <th>الشيكات المستخدمة حاليا</th>
                            <th>تحكم</th>
                        </tr>
                        </thead>
                        <tbody id="prcoess_items">
                        @forelse ($bankProfile->chequeBooks as $cheque)
                            <tr role="row">
                                <td>{{ $cheque->name }}</td>
                                <td>{{ $cheque->start_number }}</td>
                                <td>{{ $cheque->end_number }}</td>
                                <td>{{ $cheque->totalUsedNumbers() }}</td>
                                <td>
                                    {{ Form::open(['method' => 'DELETE', 'route' => ['bank-profile.cheque-book.destroy', $bankProfile->id, $cheque->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
                                    {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
                                    {{ Form::close() }}
                                    {{ link_to_route('bank-profile.cheque-book.edit', 'تعديل', ['bank_profile' => $bankProfile->id, 'cheque_book' => $cheque->id], array('class' => 'btn btn-primary')) }}
                                    {{ link_to_route('bankCash.chequeBooks', 'الشيكات', ['bankId' => $bankProfile->id, 'chequeBookId' => $cheque->id], array('class' => 'btn btn-primary')) }}
                                </td>
                            </tr>
                        @empty
                            <tr>ﻻ يوجد شيكات.</tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-3 ">
                    <div class="form-group">
                        <label for="inputError" class="control-label">
                        </label>
                        <button class="btn btn-lg btn-block btn-success" type="button" id="btnUpdateJobProfile">
                            إضافة دفتر جديد
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>

</div>
@endif

@endsection


@section('scripts')
    <script>
        @if(isset($bankId))
        $('#btnUpdateJobProfile').on({
            'click': function () {
                var link = "{{ route('bank-profile.cheque-book.create', ['bankId' => $bankProfile->id]) }}";
                window.location.href = link;
            }
        });
        @endif
    </script>
@endsection