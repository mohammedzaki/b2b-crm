<div class="col-lg-12 no-padding">

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

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover"
               id="dataTables-example">
            <thead>
            <tr>
                <th width="10%">تاريخ</th>
                <th width="20%">المتبقى</th>
                <th width="10%">المدفوع</th>
                <th width="15%">الاجمالى</th>
                <th width="10%">سعر الوحدة</th>
                <th width="5%">الكمية</th>
                <th width="30%">بيان</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($processes as $process)
                <tr class="odd">
                    <th style="background-color: #716e6eeb; color: white;">
                        <div class="no-padding">
                            <label>مسلسل :</label> {{ $process['processNum'] }}
                        </div>
                    </th>
                    <th style="background-color: #716e6eeb; color: white;">
                        <div class="no-padding">
                            <label>اسم العملية :</label> {{ $process['processName'] }}
                        </div>
                    </th>
                    <th style="background-color: #716e6eeb; color: white;"></th>
                    <th style="background-color: #716e6eeb; color: white;"></th>
                    <th style="background-color: #716e6eeb; color: white;"></th>
                    <th style="background-color: #716e6eeb; color: white;"></th>
                    <th style="background-color: #716e6eeb; color: white;"></th>
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
                    <td></td>
                    <td></td>
                    <td style="color: red;">
                        الاجمالـــــــــــــــــــــــــــــــــــى
                    </td>
                </tr>
                <tr style="background-color: black;">
                    <td style="background-color: black;"></td>
                    <td style="background-color: black;"></td>
                    <td style="background-color: black;"></td>
                    <td style="background-color: black;"></td>
                    <td style="background-color: black;"></td>
                    <td style="background-color: black;"></td>
                    <td style="background-color: black;"></td>
                </tr>
            @empty
            @endforelse

            <tr class="info">
                <td></td>
                <td style="color: red;">{{ $allProcessTotalRemaining }}</td>
                <td style="color: red;">{{ $allProcessTotalPaid }}</td>
                <td style="color: red;">{{ $allProcessesTotalPrice }}</td>
                <td></td>
                <td></td>
                <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- /.table-responsive -->
</div>