@extends("layouts.app") 
@section("title", "تقرير المصروفات - التقارير")
@section("content")

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">التقارير <small>تقرير المصروفات</small></h1>
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
                المصروفات
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(["route" => "reports.employees.viewReport", 'id' => 'depositwithdrawForm']) }}
            <div class="panel-body">

                <div class="legend">
                    {{ Form::label("selectMonth", "الشهر") }}
                    {{ Form::text("selectMonth", null, array(
                            "id" => "selectMonth",
                            "class" => "form-control ",
                            "required" => "required")) 
                    }}
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
                                    <th rowspan="1" colspan="1" >اسم المصروف</th>
                                </tr>
                            </thead>
                            <tbody id="grid_SelectedIds">
                                @forelse ($employees as $index => $employee)
                                <tr class="odd">
                                    <td> <input class="" type="checkbox" value="1" onchange="SelectId(this)"> </td>
                                    <td> {{ $employee['name'] }} </td>
                                    <td hidden>
                                        <input class="form-control expenseId" disabled="disabled" name="selectedIds[{{ $index }}]" type="hidden" value="{{ $employee['id'] }}">
                                    </td>
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
    function SelectAll(ch_all) {
        var rowsCount = $("#grid_SelectedIds").children().length;
        if ($(ch_all).is(":checked")) {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", true);
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") .expenseId").prop("disabled", false);
            }
        } else {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", false);
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") .expenseId").prop("disabled", true);
            }
        }
    }

    function SelectId(CurrentCell) {
        if ($("#grid_SelectedIds").children().length > 0) {
            rowIndex = $(CurrentCell)
                    .closest('tr') // Get the closest tr parent element
                    .prevAll() // Find all sibling elements in front of it
                    .length; // Get their count

            var inputSelectedId = $("#grid_SelectedIds tr:eq(" + rowIndex + ") .expenseId");
            if (inputSelectedId.is(":disabled")) {
                inputSelectedId.prop("disabled", false);
            } else {
                inputSelectedId.prop("disabled", true);
            }
        }
    }
    
    $(function () {
        $("#selectMonth").flatpickr({
            enableTime: false,
            maxDate: new Date(),
            defaultDate: new Date(),
            altInput: true,
            altFormat: "l, j F, Y",
            locale: "ar"
        });
    });
</script>
@endsection