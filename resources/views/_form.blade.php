<div class="dataTable_wrapper">
    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <div class="row">
            <div class="col-sm-6">
                <label class="date"> 
                    <div align="center">
                        <table width="100%" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" border="0"  > 
                            <tr> 
                                <td class="alt1"> 
                                    <div class="alt2Active" style="padding:6px; overflow:auto"> 
                                        <div id="postmenu_344900"> 
                                            <!-- clock hack --> 
                                            <div id="clock">Loading...</div> 
                                            <script type="text/javascript">
                                                function refrClock()
                                                {
                                                    var d = new Date();
                                                    var day = d.getDay();
                                                    var date = d.getDate();
                                                    var month = d.getMonth();
                                                    var year = d.getFullYear();
                                                    var days = new Array("الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت");
                                                    var months = new Array("ديسمبر", "نوفمبر", "اكتوبر", "سبتمبر", "أغسطس", "يوليو", "يونيو", "مايو", "ابريل", "مارس", "فبراير", "يناير");

                                                    document.getElementById("clock").innerHTML = days[day] + " " + date + " " + months[month] + " " + year;
                                                    setTimeout("refrClock()", 1000);
                                                }
                                                refrClock();
                                            </script> 
                                        </div>
                                    </div>
                                </td> 
                            </tr> 
                        </table> 
                    </div>
                </label>
            </div>

            <div class="col-sm-6 text-left">
                <div id="dataTables-example_filter" class="dataTables_filter">
                    <button type="button" class="btn btn-primary disabled"> 
                        <label>الرصيد الحالى : {{ $numbers['current_amount'] }} جنيه</label>
                    </button>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                    <thead>
                        <tr role="row">
                            <th  rowspan="1" colspan="1" style="width:20px;" >اختيار</th>
                            <th  rowspan="1" colspan="1" >وارد</th>
                            <th  rowspan="1" colspan="1" >منصرف</th>
                            <th  rowspan="1" colspan="1" >بيان</th>
                            <th  rowspan="1" colspan="1" >اسم العملية</th>
                            <th  rowspan="1" colspan="1" >اسم العميل</th>
                            <th  rowspan="1" colspan="1" >اسم المورد</th>
                            <th  rowspan="1" colspan="1" >اسم الموظف</th>
                            <th  rowspan="1" colspan="1" >اسم المصروف</th>
                            <th  rowspan="1" colspan="1" >طريقة الدفع</th>
                            <th  rowspan="1" colspan="1" >ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="gradeA odd" role="row">

                            <td><input type="checkbox" value=""></td>
                            <td>

                                <div class="form-group{{ $errors->has('depositValue') ? ' has-error' : '' }}">
                                    <input style="width:85px" id="supplyValue" name="depositValue" class="form-control">
                                    @if ($errors->has('depositValue'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('depositValue') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="form-group{{ $errors->has('withdrawValue') ? ' has-error' : '' }}">
                                    <input style="width:85px" id="withdrawValue" name="withdrawValue" class="form-control">
                                    @if ($errors->has('withdrawValue'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('withdrawValue') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="form-group{{ $errors->has('recordDesc') ? ' has-error' : '' }}">
                                    <input style="width:85px" id="recordDesc" name="recordDesc" class="form-control">
                                    @if ($errors->has('recordDesc'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('recordDesc') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="form-group{{ $errors->has('cbo_processes') ? ' has-error' : '' }}">
                                    <select id="cbo_processes" name="cbo_processes" style="width:85px" class="form-control">
                                        <option value="">اختر اسم العملية</option>
                                    </select>
                                    @if ($errors->has('cbo_processes'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('cbo_processes') }}
                                    </label>
                                    @endif
                                </div>

                            </td>
                            <td>

                                <div class="form-group{{ $errors->has('client_id') ? ' has-error' : '' }}">
                                    {{ Form::select('client_id', $clients, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم العميل',
                            'id' => 'client_id',
                            'style' => 'width:85px;')
                        )
                                    }}
                                    @if ($errors->has('client_id'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('client_id') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>

                                <div class="form-group{{ $errors->has('supplier_id') ? ' has-error' : '' }}">
                                    {{ Form::select('supplier_id', $suppliers, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم المورد',
                            'id' => 'supplier_id',
                            'style' => 'width:85px;')
                        )
                                    }}
                                    @if ($errors->has('supplier_id'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('supplier_id') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>

                                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                    {{ Form::select('employee_id', $employees, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم المورد',
                            'id' => 'emplyee_id',
                            'style' => 'width:85px;')
                        )
                                    }}
                                    @if ($errors->has('employee_id'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('employee_id') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>

                                <div class="form-group{{ $errors->has('expenses_id') ? ' has-error' : '' }}">
                                    {{ Form::select('expenses_id', $expenses, null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'اسم المصروف',
                            'id' => 'expenses_id',
                            'style' => 'width:85px;')
                        )
                                    }}
                                    @if ($errors->has('expenses_id'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('expenses_id') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="form-group{{ $errors->has('payMethod') ? ' has-error' : '' }}">
                                    <select style="width:85px" id="payMethod" name="payMethod" class="form-control">
                                        <option>اختر</option>
                                        <option value="0">كاش</option>
                                        <option value="1">شيك</option>
                                    </select>
                                    @if ($errors->has('payMethod'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('payMethod') }}
                                    </label>
                                    @endif
                                </div>

                            </td>
                            <td>
                                <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                                    <input style="width:85px" id="notes" name="notes" class="form-control">
                                    @if ($errors->has('notes'))
                                    <label for="inputError" class="control-label">
                                        {{ $errors->first('notes') }}
                                    </label>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <button type="reset" class="btn btn-success">جديد</button>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
        <div class="col-md-6 text-left">
            <!-- Date Picker-->
            <input type="text" style="width:100px"  id="datepicker" class="form-control">
            <script>
                $(function () {
                    $("#datepicker").datepicker();
                });
            </script>
            <button type="button" class="btn btn-danger">بحث</button>
        </div>
    </div>
</div> 