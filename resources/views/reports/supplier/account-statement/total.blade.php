@extends($reportLayout)
@section('reportTitle', 'كشف حساب مورد مجمع')
@section("title", "كشف حساب مورد مجمع - التقارير")
@section('styles')
    <link href="/{{$reportStyle}}" rel="stylesheet">
@endsection
@section('reportHeader')
    <div class="col-lg-12 no-padding">
        <table align="center">
            <tr>
                <td width="30%">
                    <div>
                        <label> اسم المورد :</label> {{ $supplierName }}
                    </div>
                </td>
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
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>اسم العملية</th>
                    <th>الاجمالى</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($processes as $process)
                    <tr class="odd">
                        <td>{{ $process['processName'] }}</td>
                        <td>{{ $process['processTotalPrice'] }}</td>
                        <td style="color: red;">{{ $process['processTotalPaid'] }}</td>
                        <td>{{ $process['processTotalRemaining'] }}</td>
                    </tr>
                @empty
                @endforelse
                <tr class="info">
                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                    <td style="color: red;">{{ $allProcessesTotalPrice }}</td>
                    <td style="color: red;">{{ $allProcessTotalPaid }}</td>
                    <td style="color: red;">{{ $allProcessTotalRemaining }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection