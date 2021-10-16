@extends($reportLayout)
@section('reportTitle', 'المركز التحليلى للموردين مجمع')
@section("title", "المركز التحليلى للموردين مجمع - التقارير")
@section('styles')
    <link href="/{{$reportStyle}}" rel="stylesheet">
@endsection
@section('reportHeader')
    <div class="col-lg-12 no-padding">
        <table align="center">
            <tr>
                <td width="40%">
                    <div>
                        <label> تاريخ :</label> {{ $date }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
@section('reportHTML')
    <div class="col-lg-12 no-padding">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>اسم المورد</th>
                    <th>الاجمالى</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($suppliers as $supplier)
                    <tr class="odd">
                        <td>{{ $supplier['supplierName'] }}</td>
                        <td>{{ $supplier['supplierTotalPrice'] }}</td>
                        <td style="color: red;">{{ $supplier['supplierTotalPaid'] }}</td>
                        <td>{{ $supplier['supplierTotalRemaining'] }}</td>
                    </tr>
                @empty
                @endforelse
                <tr class="info">
                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                    <td style="color: red;">{{ $allSuppliersTotalPrice }}</td>
                    <td style="color: red;">{{ $allSuppliersTotalPaid }}</td>
                    <td style="color: red;">{{ $allSuppliersTotalRemaining }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection