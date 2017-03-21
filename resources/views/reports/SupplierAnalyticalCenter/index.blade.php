@extends("layouts.app") 
@section("title", "المركز التحليلى للموردين - التقارير")
@section("content")

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">التقارير <small>المركز التحليلى للموردين</small></h1>
    </div>
</div>
<!-- /.row -->

<div class="row">

    @if (session("success"))
    <div class="alert alert-success">
        {{ session("success") }}
    </div>
    @endif

    @if (session("error"))
    <div class="alert alert-danger">
        {{ session("error") }}
    </div>
    @endif

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                تقرير عملية مورد
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(["route" => "reports.SupplierAnalyticalCenter.viewSupplierReport", 'id' => 'depositwithdrawForm']) }}
            <div class="panel-body">

                <div class="legend">

                    {{ Form::radio("ch_detialed", "1", null, 
                        array(
                            "id" => "ch_detialed",
                            "class" => "checkbox_show_input",
                            "required" => "required"
                        )
                    ) }} 
                    {{ Form::label("ch_detialed", "مفصل") }}
                    &ensp;
                    {{ Form::radio("ch_detialed", "0", null, 
                        array(
                            "id" => "ch_total",
                            "class" => "checkbox_show_input",
                            "required" => "required"
                        )
                    ) }} 
                    {{ Form::label("ch_total", "مجمع") }}
                </div>
                <div class="legend">
                    {{ Form::checkbox(null, "1", null, 
                        array(
                            "id" => "ch_openprocess",
                            "class" => "checkbox_show_input",
                            "onchange" => "FillterProcess()",
                            "checked" => "checked"
                        )
                    ) }} 
                    {{ Form::label(null, "عمليات مفتوحة") }}
                    &ensp;
                    {{ Form::checkbox(null, "1", null, 
                        array(
                            "id" => "ch_closedprocess",
                            "class" => "checkbox_show_input",
                            "onchange" => "FillterProcess()"
                        )
                    ) }} 
                    {{ Form::label(null, "عمليات مغلقة") }}
                </div>
                <div class="legend">
                    {{ Form::checkbox(null, "1", null, 
                        array(
                            "id" => "ch_all",
                            "class" => "checkbox_show_input",
                            "onchange" => "SelectAll(this)"
                        )
                    ) }} 
                    {{ Form::label("ch_all", "الكل") }}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="depositwithdrawTable" role="grid" aria-describedby="dataTables-example_info">
                            <thead>
                                <tr role="row">
                                    <th rowspan="1" colspan="1" style="padding: 8px;">اختيار</th>
                                    <th rowspan="1" colspan="1" >اسم المورد</th>
                                    <th rowspan="1" colspan="1" >اجمالي المدفوع</th>
                                    <th rowspan="1" colspan="1" >اجمالي المستحق</th>
                                </tr>
                            </thead>
                            <tbody id="grid_SupplierProcess">
                                @forelse ($suppliers as $supplier)
                                <tr class="odd">
                                    <td> <input class="" type="checkbox" value="1" > </td>
                                    <td> {{ $supplier['name'] }} </td>
                                    <td> {{ $supplier['totalPaid'] }} </td>
                                    <td> {{ $supplier['totalRemaining'] }} </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::checkbox("withLetterHead", "1", 1, 
                            array(
                                "id" => "withLetterHead",
                                "class" => "checkbox_show_input"
                            )
                        ) }} 
                        {{ Form::label("withLetterHead", "طباعة الليتر هد") }}
                        <br>
                        <button type="submit" class="btn btn-primary center-block" style="padding: 6px 30px;">عرض</button>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            {{ Form::close() }}
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection

@section('scripts')
<script>
    //var supplierProcess = null;
    var currentSupplierId = 0;

    function GetSupplierProcess(supplier_id) {
        currentSupplierId = $(supplier_id).val();
        FillterProcess();
    }

    function SelectAll(ch_all) {
        var rowsCount = $("#grid_SupplierProcess").children().length;
        if ($(ch_all).is(":checked")) {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", true);
                $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0).prop("disabled", false);
            }
        } else {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", false);
                $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0).prop("disabled", true);
            }
        }
    }

    function FillterProcess() {
        /*$("#grid_SupplierProcess").empty();
         var index = 0;
         if (allProcesses != null) {
         $.each(allProcesses, function (key, value) {
         if (value.clientId == currentSupplierId) {
         if ($("#ch_openprocess").is(":checked") && value.status == "active") {
         $("#grid_SupplierProcess").append('<tr class="gradeA odd ItemRow" role="row"> <td style="text-align:center; vertical-align: middle;"> <input class="" type="checkbox" value="1" onchange="SelectProcess(this)"> </td><td> <input class="form-control" disabled="disabled" name="processName" type="text" value="' + value.name + '"> </td><td> <input class="form-control" disabled="disabled" name="processTotal" type="text"  value="' + value.totalPrice + '"> </td> <td hidden><input class="form-control" disabled="disabled" name="processes[' + index + ']" type="hidden" value="' + value.id + '"></td></tr>');
         }
         if ($("#ch_closedprocess").is(":checked") && value.status == "closed") {
         $("#grid_SupplierProcess").append('<tr class="gradeA odd ItemRow" role="row"> <td style="text-align:center; vertical-align: middle;"> <input class="" type="checkbox" value="1" onchange="SelectProcess(this)"> </td><td> <input class="form-control" disabled="disabled" name="processName" type="text" value="' + value.name + '"> </td><td> <input class="form-control" disabled="disabled" name="processTotal" type="text"  value="' + value.totalPrice + '"> </td> <td hidden><input class="form-control" disabled="disabled" name="processes[' + index + ']" type="hidden" value="' + value.id + '"></td></tr>');
         }
         index++;
         }
         });
         }*/
    }

    function SelectProcess(CurrentCell) {
        /*if ($("#grid_SupplierProcess").children().length > 0) {
         rowIndex = $(CurrentCell)
         .closest('tr') // Get the closest tr parent element
         .prevAll() // Find all sibling elements in front of it
         .length; // Get their count
         
         var inputProcessId = $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0);
         if (inputProcessId.is(":disabled")) {
         $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0).prop("disabled", false);
         } else {
         $("#grid_SupplierProcess tr:eq(" + rowIndex + ") td:eq(3)").children(0).prop("disabled", true);
         }
         }*/
    }


</script>
@endsection