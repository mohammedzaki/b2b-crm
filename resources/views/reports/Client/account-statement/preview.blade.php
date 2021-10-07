@extends("layouts.app")
@section("title", "تقرير عملية عميل - التقارير")
@section("content")

    <div class="row">
        {{ Form::open(["route" => "reports.client.accountStatement.printDetailedPDF", "method" => "GET"]) }}
        <row>
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12 no-padding">
                        <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;"/>
                    </div>
                    <div class="col-lg-12 no-padding">
                        <div class="col-lg-6 no-padding">
                            <label>اسم العميل :</label> {{ $clientName }}
                        </div>
                        <div class="col-lg-6 no-padding">
                            <label>تاريخ :</label> {{ $date }}
                        </div>
                    </div>

                    <div class="col-lg-12 no-padding">

                        <div class="col-lg-12 no-padding">
                            <div class="panel-body">

                                <div class="dataTable_wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover"
                                               id="dataTables-example">
                                            <thead>
                                            <tr>
                                                <th width="10%">تاريخ</th>
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
                                                <tr class="odd">
                                                    <th style="background-color: #eeeeeeeb;">
                                                        <div class="no-padding">
                                                            <label>مسلسل :</label> {{ $process['processNum'] }}
                                                        </div>
                                                    </th>
                                                    <th style="background-color: #eeeeeeeb;" colspan="6">
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
                                                    <td style="color: red;"
                                                        colspan="3">{{ $process['processTotalPrice'] }}</td>
                                                    <td style="color: red;">
                                                        الاجمالـــــــــــــــــــــــــــــــــــى
                                                    </td>
                                                </tr>
                                                <tr style="background-color: black;">
                                                    <td style="background-color: black;" colspan="7"></td>
                                                </tr>
                                            @empty
                                            @endforelse

                                            <tr class="info">
                                                <td></td>
                                                <td style="color: red;">{{ $allProcessTotalRemaining }}</td>
                                                <td style="color: red;">{{ $allProcessTotalPaid }}</td>
                                                <td style="color: red;" colspan="3">{{ $allProcessesTotalPrice }}</td>
                                                <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </row>

        <row class="col-lg-12 clearboth">
            <p class="text-center">
                {{ Form::hidden("ch_detialed", "1", null) }}
                {{ Form::checkbox("withLetterHead", "1", 1,
                            array(
                                "id" => "withLetterHead",
                                "class" => "checkbox_show_input"
                            )
                        ) }}
                {{ Form::label("withLetterHead", "طباعة الليتر هد") }}
                <br>
                <button class="btn btn-primary" type="submit">طباعة</button>
            </p>

        </row>
        {{ Form::close() }}
    </div>



@endsection