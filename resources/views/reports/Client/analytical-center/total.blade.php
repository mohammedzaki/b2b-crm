@extends($reportLayout)
@section('reportTitle', 'المركز التحليلى للعملاء مجمع')
@section("title", "المركز التحليلى للعملاء مجمع - التقارير")
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
                    <th>اسم العميل</th>
                    <th>الاجمالى</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($clients as $client)
                    <tr class="odd">
                        <td>{{ $client['clientName'] }}</td>
                        <td>{{ $client['clientTotalPrice'] }}</td>
                        <td style="color: red;">{{ $client['clientTotalPaid'] }}</td>
                        <td>{{ $client['clientTotalRemaining'] }}</td>
                    </tr>
                @empty
                @endforelse
                <tr class="info">
                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                    <td style="color: red;">{{ $allClientsTotalPrice }}</td>
                    <td style="color: red;">{{ $allClientsTotalPaid }}</td>
                    <td style="color: red;">{{ $allClientsTotalRemaining }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection