@extends($reportLayout)
@section('reportTitle', 'المركز التحليلى للعملاء مفصل')
@section("title", "المركز التحليلى للعملاء مفصل - التقارير")
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
            @forelse ($clients as $client)
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr class="processHeader">
                        <th>
                            <div>
                                <label> مسلسل :</label> {{ $client['clientNum'] }}
                            </div>
                        </th>
                        <th colspan="6">
                            <div>
                                <label> اسم العميل :</label> {{ $client['clientName'] }}
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>اسم العملية</th>
                        <th>الاجمالى</th>
                        <th>المدفوع</th>
                        <th>المتبقى</th>
                        <th>تاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($client['processDetails'] as $details)
                        <tr class="odd">
                            <td> {{ $details['name'] }} </td>
                            <td> {{ $details['totalPrice'] }} </td>
                            <td style="color: red;"> {{ $details['paid'] }} </td>
                            <td> {{ $details['remaining'] }} </td>
                            <td> {{ $details['date'] }} </td>
                        </tr>
                    @empty
                    @endforelse
                    <tr class="info">
                        <td></td>
                        <td style="color: red;">{{ $client['clientTotalPrice'] }}</td>
                        <td style="color: red;">{{ $client['clientTotalPaid'] }}</td>
                        <td style="color: red;">{{ $client['clientTotalRemaining'] }}</td>
                        <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                    </tr>
                    </tbody>
                </table>
            @empty
            @endforelse
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection