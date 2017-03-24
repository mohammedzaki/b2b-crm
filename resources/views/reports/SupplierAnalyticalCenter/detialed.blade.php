@extends("layouts.app") 
@section("title", "المركز التحليلى للموردين - التقارير")
@section("content")

<div class="row">
    <form role="form" action="printDetailedPDF" method="GET">
        <div class="col-lg-12" id="printcontent">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12 no-padding">
                        <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;" />
                    </div>
                    @forelse ($suppliers as $supplier)
                    <div class="col-lg-12 no-padding">
                        <div class="col-lg-6 no-padding">
                            <label>اسم المورد :</label> {{ $supplier['supplierName'] }}
                        </div>
                        <div class="col-lg-6 no-padding">
                            <label>مسلسل :</label> {{ $supplier['supplierNum'] }}
                        </div>
                        <div class="col-lg-12 no-padding">
                            <div class="panel-body">

                                <div class="dataTable_wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>اسم العملية</th>
                                                    <th>الاجمالى</th>
                                                    <th>المدفوع</th>
                                                    <th>المتبقى</th>
                                                    <th>تاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($supplier['processDetails'] as $details)
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
                                                    <td style="color: red;">{{ $supplier['supplierTotalPrice'] }}</td>
                                                    <td style="color: red;">{{ $supplier['supplierTotalPaid'] }}</td>
                                                    <td style="color: red;">{{ $supplier['supplierTotalRemaining'] }}</td>
                                                    <td style="color: red;"> الاجمالـــــــــــــــــــــــــــــــــــى</td>
                                                </tr>
                                            </tbody>
                                        </table>  
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

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
    </form>
</div>



@endsection