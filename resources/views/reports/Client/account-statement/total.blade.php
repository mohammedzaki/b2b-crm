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
        <table class="table table-striped table-bordered table-hover">
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
</div>