@extends($reportLayout)
@section('reportTitle', 'كشف حساب عميل مفصل')
@section('reportHeader')
    <div class="col-lg-12 no-padding">
        <table align="center" width="90%">
            <tr>
                <td width="15%" align="right">اسم العميل :</td>
                <td width="50%" align="right">{{ $clientName }}</td>
                <td width="10%" align="right">تاريخ :</td>
                <td width="25%" align="right">{{ $date }}</td>
            </tr>
        </table>
        <br />
    </div>
@endsection
@section('reportHTML')
    <div class="col-lg-12 no-padding">

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th width="15%" align="right">تاريخ</th>
                    <th width="10%" align="right">المتبقى</th>
                    <th width="10%" align="right">المدفوع</th>
                    <th width="15%" align="right">الاجمالى</th>
                    <th width="10%" align="right">سعر الوحدة</th>
                    <th width="10%" align="right">الكمية</th>
                    <th width="30%" align="right">بيان</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($processes as $process)
                    <tr class="odd">
                        <th colspan="2" style="background-color: #716e6eeb; color: white;">
                            <div class="no-padding">
                                <label>رقم العملية :</label> {{ $process['processNum'] }}
                            </div>
                        </th>
                        <th colspan="5" style="background-color: #716e6eeb; color: white;">
                            <div class="no-padding">
                                <label>اسم العملية :</label> {{ $process['processName'] }}
                            </div>
                        </th>
                    </tr>
                    @forelse ($process['processDetails'] as $details)
                        <tr class="odd">
                            <td> {{ $details['date'] }} </td>
                            <td> {{ $details['remaining'] }} </td>
                            <td style="color: red;"> {{ $details['paid'] }} </td>
                            <td> {{ $details['totalPrice'] }} </td>
                            <td> {{ $details['unitPrice'] }} </td>
                            <td> {{ $details['quantity'] }} </td>
                            <td> {{ $details['desc'] }} </td>
                        </tr>
                    @empty
                    @endforelse
                    <tr class="info">
                        <td></td>
                        <td style="color: red;">{{ $process['processTotalRemaining'] }}</td>
                        <td style="color: red;">{{ $process['processTotalPaid'] }}</td>
                        <td style="color: red;">{{ $process['processTotalPrice'] }}</td>
                        <td colspan="3" style="color: red;">
                            الاجمالـــــــــــــــــــــــــــــــــــى
                        </td>
                    </tr>
                @empty
                @endforelse
                <tr style="background-color: black; color: white;">
                    <td align="center" colspan="7" style="background-color: black; color: white;">
                        الاجمالـــــــــــــــــــــــــــــــــــى
                    </td>
                </tr>
                <tr class="info">
                    <td></td>
                    <td style="color: red;">{{ $allProcessTotalRemaining }}</td>
                    <td style="color: red;">{{ $allProcessTotalPaid }}</td>
                    <td colspan="3" style="color: red;">{{ $allProcessesTotalPrice }}</td>
                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection