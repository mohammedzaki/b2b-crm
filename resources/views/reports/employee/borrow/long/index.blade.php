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
            {{ Form::open(["route" => "reports.employees.borrow.long.viewReport"]) }}
            <div class="panel-body">

                <div class="legend">
                    {{ Form::checkbox(null, "1", null, 
                        array(
                            "id" => "ch_UnpaidBorrows",
                            "class" => "checkbox_show_input",
                            "onchange" => "LoadEmployees()",
                            "checked" => "checked"
                        )
                    ) }} 
                    {{ Form::label(null, "سلف جارية") }}
                    &ensp;
                    {{ Form::checkbox(null, "1", null, 
                        array(
                            "id" => "ch_PaidBorrows",
                            "class" => "checkbox_show_input",
                            "onchange" => "LoadEmployees()"
                        )
                    ) }} 
                    {{ Form::label(null, "سلف منتهية") }}
                </div>
                <div class="form-group">
                    {{ Form::label("employee_id", "اسم الموطف") }}
                    {{ Form::select("employee_id", [], null,
                                                        array(
                                                            "class" => "form-control",
                                                            "placeholder" => "",
                                                            "id" => "employee_id",
                                                            "onchange" => "GetEmployeeBorrows(this)")
                                                        )
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
                                    <th rowspan="1" colspan="1" >البيان</th>
                                    <th rowspan="1" colspan="1" >اجمالى السلفة</th>
                                    <th rowspan="1" colspan="1" >اجمالي المدفوع</th>
                                    <th rowspan="1" colspan="1" >اجمالي المستحق</th>
                                </tr>
                            </thead>
                            <tbody id="grid_EmployeeBorrows">
                                <!--
                                <tr class="odd">
                                    <td> <input class="employeeCheck" type="checkbox" value="1" onchange="SelectId(this)"> </td>
                                    <td> desc </td>
                                    <td> total </td>
                                    <td> totalPaid </td>
                                    <td> totalRemaining </td>
                                    <td hidden>
                                        <input class="form-control employeeId" disabled="disabled" name="selectedIds[0]" type="hidden" value="borrow_id">
                                    </td>
                                </tr>
                                -->
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
    var employees = {!! $employees !!};
    var currentEmployeeId = 0;
    var grid_EmployeeBorrows = $("#grid_EmployeeBorrows");
    var employee_id = $('#employee_id');
    
    LoadEmployees();

    function LoadEmployees() {
        currentEmployeeId = employee_id.val();
        employee_id.empty();
        grid_EmployeeBorrows.empty();
        if (employees !== null) {
            employee_id.append('<option></option>');
            $.each(employees, function (id, employee) {
                //if (($("#ch_UnpaidBorrows").is(":checked") && employee.hasUnpaidBorrows == true) || ($("#ch_paidBorrows").is(":checked") && employee.hasPaidBorrows == true)) {
                    if (id == currentEmployeeId) {
                        employee_id.append('<option selected value="' + employee.id + '">' + employee.name + '</option>');
                        FillterBorrows();
                    } else {
                        employee_id.append('<option value="' + employee.id + '">' + employee.name + '</option>');
                    }
                //}
            });
        }
    }
    
    function GetEmployeeBorrows(client_id) {
        currentEmployeeId = $(client_id).val();
        FillterBorrows();
    }
    
    function FillterBorrows() {
        grid_EmployeeBorrows.empty();
        var index = 0;
        if (employees[currentEmployeeId].borrows != null) {
            $.each(employees[currentEmployeeId].borrows, function (borrowId, borrow) {
                if (($("#ch_UnpaidBorrows").is(":checked") && borrow.active == true) || ($("#ch_PaidBorrows").is(":checked") && borrow.active == false)) {
                    grid_EmployeeBorrows.append('\n\
                        <tr class="odd"> \n\
                            <td> <input class="employeeCheck" type="checkbox" value="1" onchange="SelectId(this)"> </td> \n\
                            <td> ' + borrow.borrow_reason + ' </td> \n\
                            <td> ' + borrow.total + ' </td> \n\
                            <td> ' + borrow.totalPaid + ' </td> \n\
                            <td> ' + borrow.totalRemaining + ' </td> \n\
                            <td hidden> <input class="form-control employeeId" disabled="disabled" name="selectedIds[' + index + ']" type="hidden" value="' + borrow.id + '"> </td> \n\
                        </tr> \n\
                    ');
                }
                index++;
            });
        }
    }
    
    function SelectAll(ch_all) {
        var rowsCount = grid_EmployeeBorrows.children().length;
        if ($(ch_all).is(":checked")) {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeCheck").prop("checked", true);
                $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeId").prop("disabled", false);
            }
        } else {
            for (var rowIndex = 0; rowIndex < rowsCount; rowIndex++) {
                $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeCheck").prop("checked", false);
                $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeId").prop("disabled", true);
            }
        }
    }

    function SelectId(CurrentCell) {
        if (grid_EmployeeBorrows.children().length > 0) {
            rowIndex = $(CurrentCell)
                    .closest('tr') // Get the closest tr parent element
                    .prevAll() // Find all sibling elements in front of it
                    .length; // Get their count

            var inputSelectedId = $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeId");
            if (inputSelectedId.is(":disabled")) {
                $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeId").prop("disabled", false);
            } else {
                $("#grid_EmployeeBorrows tr:eq(" + rowIndex + ") .employeeId").prop("disabled", true);
            }
        }
    }
    
</script>
@endsection