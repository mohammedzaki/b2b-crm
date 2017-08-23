@extends("layouts.app") 
@section("title", "سلفيات الموظفين- التقارير")
@section("content")

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">التقارير <small>سلفيات الموظفين</small></h1>
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
                سلفيات الموظفين
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(["route" => "reports.employees.borrow.viewReport"]) }}
            <div class="panel-body">

                <div class="legend">

                    {{ Form::radio("ch_longBorrow", "1", null, 
                        array(
                            "id" => "ch_longBorrow",
                            "class" => "checkbox_show_input",
                            "required" => "required"
                        )
                    ) }} 
                    {{ Form::label("ch_longBorrow", "سلفة مستديمة") }}
                    &ensp;
                    {{ Form::radio("ch_longBorrow", "0", null, 
                        array(
                            "id" => "ch_smallBorrow",
                            "class" => "checkbox_show_input",
                            "required" => "required"
                        )
                    ) }} 
                    {{ Form::label("ch_smallBorrow", "سلف يومية") }}
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
                                    <th rowspan="1" colspan="1" >اسم الموظف</th>
                                    <th rowspan="1" colspan="1" >اجمالى السلف المستديمة</th>
                                    <th rowspan="1" colspan="1" >اجمالي المدفوع</th>
                                    <th rowspan="1" colspan="1" >اجمالي المستحق</th>
                                    <th rowspan="1" colspan="1" >اجمالى السلف اليومية</th>
                                </tr>
                            </thead>
                            <tbody id="grid_SelectedIds">
                                @forelse ($employees as $index => $employee)
                                <tr class="odd">
                                    <td> <input class="" type="checkbox" value="1" onchange="SelectId(this)"> </td>
                                    <td> {{ $employee['name'] }} </td>
                                    <td> {{ $employee['totalLongBorrows'] }} </td>
                                    <td> {{ $employee['totalPaid'] }} </td>
                                    <td> {{ $employee['totalRemaining'] }} </td>
                                    <td> {{ $employee['totalSmallBorrows'] }} </td>
                                    <td hidden>
                                        <input class="form-control employeeId" disabled="disabled" name="selectedIds[{{ $index }}]" type="hidden" value="{{ $employee['id'] }}">
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
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(6)").children(0).prop("disabled", false);
            }
        } else {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(0)").children(0).prop("checked", false);
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(6)").children(0).prop("disabled", true);
            }
        }
    }

    function SelectId(CurrentCell) {
        if ($("#grid_SelectedIds").children().length > 0) {
            rowIndex = $(CurrentCell)
                    .closest('tr') // Get the closest tr parent element
                    .prevAll() // Find all sibling elements in front of it
                    .length; // Get their count

            var inputSelectedId = $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(6)").children(0);
            if (inputSelectedId.is(":disabled")) {
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(6)").children(0).prop("disabled", false);
            } else {
                $("#grid_SelectedIds tr:eq(" + rowIndex + ") td:eq(6)").children(0).prop("disabled", true);
            }
        }
    }
</script>
@endsection