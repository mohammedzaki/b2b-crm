@extends('layouts.app') 
@section('title', 'عرض الكل - عمليه جديدة عميل') 
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">الفواتير <small>عرض الكل</small></h1>
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

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                الفواتير
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>تاريخ التحصيل</th>
                                    <th>اسم العميل</th>
                                    <th>العمليات</th>
                                    <th>الاجمالى</th>
                                    <th>المدفوع</th>
                                    <th>المتبقى</th>
                                    <th>الحالة</th>
                                    <th width="150">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                <tr role="row">
                                    <td class="text-center">{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->invoice_date }}</td>
                                    <td>{{ $invoice->invoice_due_date }}</td>
                                    <td>{{ $invoice->client->name }}</td>
                                    <td>{{ $invoice->processesNames }}</td>
                                    <td>{{ $invoice->total_price }}</td>
                                    <td>{{ $invoice->totalPaid() }}</td>
                                    <td>{{ $invoice->totalRemaining() }}</td>
                                    <td>{{ $invoice->status == 1 ? 'تم التحصيل' : 'غير محصلة' }}</td>
                                    <td>
                                        @if ($invoice->status == 0)
                                        {{ link_to_route('invoice.edit', 'تعديل', array('id' => $invoice->id), array('class' => 'btn btn-primary')) }}
                                        {{ link_to_route('invoice.show', 'عرض', array('id' => $invoice->id), array('class' => 'btn btn-primary')) }}
                                        {{ Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id], 'onsubmit' => 'return ConfirmDelete()', 'style' => 'display: inline-block;']) }}
                                        {{ Form::button('حذف', array('type' => 'submit', 'class' => 'btn btn-danger')) }}
                                        {{ Form::close() }}
                                        
                                        {{ Form::open(['method' => 'GET', 'route' => ['invoice.pay', $invoice->id], 'onsubmit' => 'return ConfirmPay()', 'style' => 'display: inline-block;']) }}
                                        {{ Form::button('تحصيل', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                                        {{ Form::close() }}
                                        @else 
                                        {{ link_to_route('invoice.show', 'عرض', array('id' => $invoice->id), array('class' => 'btn btn-primary')) }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>ﻻ يوجد فواتير.</tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection

@section('scripts')
<script>
    function ConfirmDelete() {
        return confirm("هل انت متأكد من حذف الفاتورة ؟");
    }
    
    function ConfirmPay() {
        return confirm("هل انت متأكد من تحصيل الفاتورة ؟");
    }

    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true,
            language: {
                url: "http://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json"
            }
        });
    });
</script>
@endsection