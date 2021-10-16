@extends($reportLayout)
@section('reportTitle', 'كشف حساب مورد مفصل')
@section("title", "كشف حساب مورد مفصل - التقارير")
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
            @forelse ($processes as $process)
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr class="processHeader">
                        <th>
                            <div>
                                <label> مسلسل :</label> {{ $process['processNum'] }}
                            </div>
                        </th>
                        <th colspan="6">
                            <div>
                                <label> اسم العملية :</label> {{ $process['processName'] }}
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>تاريخ</th>
                        <th>المتبقى</th>
                        <th>المدفوع</th>
                        <th>الاجمالى</th>
                        <th>سعر الوحدة</th>
                        <th>الكمية</th>
                        <th>بيان</th>
                        @if($withUserLog) <th>السجل</th> @endif
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($process['processDetails'] as $details)
                        <tr class="odd @if(isset($details['pending'])) isPending @endif @if(isset($details['deleted'])) isDeleted @endif">
                            <td> {{ $details['date'] }} </td>
                            <td> {{ $details['remaining'] }} </td>
                            <td> {{ $details['paid'] }} </td>
                            <td> {{ $details['totalPrice'] }} </td>
                            <td> {{ $details['unitPrice'] }} </td>
                            <td> {{ $details['quantity'] }} </td>
                            <td> {{ $details['desc'] }} </td>
                            @if($withUserLog) <td> @if(isset($details['id'])) {{ link_to_route('userLog.search', 'عرض', array('row_id' => $details['id']), array('class' => 'btn btn-primary')) }} @endif </td> @endif
                        </tr>
                    @empty
                    @endforelse
                    <tr class="info">
                        <td style="color: red;"></td>
                        <td style="color: red;">{{ $process['processTotalRemaining'] }}</td>
                        <td style="color: red;">{{ $process['processTotalPaid'] }}</td>
                        <td style="color: red;"
                            colspan="3">{{ $process['processTotalPrice'] }}</td>
                        <td style="color: red;">
                            الاجمالـــــــــــــــــــــــــــــــــــى
                        </td>
                    </tr>
                    </tbody>
                </table>
                @if(count($processes) > 1)
                    <pagebreak></pagebreak>
                @endif
            @empty
            @endforelse
            @if(count($processes) > 1)
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
                @endif
        </div>
        <!-- /.table-responsive -->
    </div>
@endsection