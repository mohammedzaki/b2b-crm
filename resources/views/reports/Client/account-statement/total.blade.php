@extends($reportLayout)
@section('reportTitle', 'كشف حساب عميل مجمع')
@section('styles')
    <link href="/{{$reportStyle}}" rel="stylesheet">
@endsection
@section('reportHeader')
    <div class="col-lg-12 no-padding">
        <table align="center">
            <tr>
                <td width="30%">
                    <div>
                        <label> اسم العميل :</label> {{ $clientName }}
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

            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>تاريخ</th>
                    <th>المتبقى</th>
                    <th>المدفوع</th>
                    <th>الاجمالى</th>
                    <th>سعر الوحدة</th>
                    <th>الكمية</th>
                    <th>بيان</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($processes as $process)

                    @forelse ($process['processDetails'] as $details)
                        <tr class="odd">
                            <td> {{ $details['date'] }} </td>
                            <td> {{ $details['remaining'] }} </td>
                            <td class="redColor"> {{ $details['paid'] }} </td>
                            <td> {{ $details['totalPrice'] }} </td>
                            <td> {{ $details['unitPrice'] }} </td>
                            <td> {{ $details['quantity'] }} </td>
                            <td> {{ $details['desc'] }} </td>
                        </tr>
                    @empty
                    @endforelse

                @empty
                @endforelse
                <tr class="info">
                    <td></td>
                    <td class="redColor">{{ $allProcessTotalRemaining }}</td>
                    <td class="redColor">{{ $allProcessTotalPaid }}</td>
                    <td colspan="3" class="redColor">{{ $allProcessesTotalPrice }}</td>
                    <td class="redColor">
                        الاجمالـــــــــــــــــــــــــــــــــــى
                    </td>
                </tr>
                </tbody>
            </table>
            <pagebreak></pagebreak>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th style="text-align: center;" colspan="7">
                        <div>
                            <label> اجمالي العمليات</label>
                        </div>
                    </th>
                </tr>
                <tr class="totalProcess">
                    <th></th>
                    <th>المتبقى</th>
                    <th>المدفوع</th>
                    <th colspan="3">الاجمالى</th>
                    <th>اسم العملية</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($processes as $process)
                    <tr class="odd">
                        <td></td>
                        <td>{{ $process['processTotalRemaining'] }}</td>
                        <td>{{ $process['processTotalPaid'] }}</td>
                        <td colspan="3">{{ $process['processTotalPrice'] }}</td>
                        <td>
                            {{ $process['processName'] }}
                        </td>
                    </tr>
                @empty
                @endforelse
                <tr class="info">
                    <td></td>
                    <td class="redColor">{{ $allProcessTotalRemaining }}</td>
                    <td class="redColor">{{ $allProcessTotalPaid }}</td>
                    <td colspan="3" class="redColor">{{ $allProcessesTotalPrice }}</td>
                    <td class="redColor">
                        الاجمالـــــــــــــــــــــــــــــــــــى
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection