@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">لوحة التحكم</h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa  fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['clients_number'] }}</div>
                        <div>عملاء</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('client') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['suppliers_number'] }}</div>
                        <div>موردين</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('supplier') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">{{ $numbers['process_number'] }}</div>
                        <div>عمليات العملاء</div>
                    </div>
                </div>
            </div>
            <a href="{{ URL::to('client/process') }}">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-left">
                        <div class="huge">130</div>
                        <div>عمليات الموردين</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">تفاصيل</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                وارد / منصرف
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
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
                                        <label>الرصيد الحالى : 1000 جنيه</label>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <form role="form">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th  rowspan="1" colspan="1" style="width:20px;" >اختيار</th>
                                            <th  rowspan="1" colspan="1" >وارد</th>
                                            <th  rowspan="1" colspan="1" >منصرف</th>
                                            <th  rowspan="1" colspan="1" >بيان</th>
                                            <th  rowspan="1" colspan="1" >اسم العملية</th>
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
                                            <td><input style="width:85px" class="form-control"></td>
                                            <td><input style="width:85px" class="form-control"></td>
                                            <td><input style="width:85px" class="form-control"></td>
                                            <td>
                                                <select style="width:85px" class="form-control">
                                                    <option>اختر</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select style="width:85px" class="form-control">
                                                    <option>اختر</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select style="width:85px" class="form-control">
                                                    <option>اختر</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select style="width:85px" class="form-control">
                                                    <option>اختر</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select style="width:85px" class="form-control">
                                                    <option>اختر</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                    <option>اسم العملية اسم العملية</option>
                                                </select>
                                            </td>
                                            <td><input style="width:85px" class="form-control"></td>
                                        </tr>
                                        
                                        </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <button type="button" class="btn btn-success">جديد</button>
                        <button type="button" class="btn btn-primary">حفظ</button>
                        <button type="button" class="btn btn-info">تصفح</button>
                        <button type="button" class="btn btn-warning">مرتبات</button>
                        </div>
                        <div class="col-md-6 text-left">
                            <!-- Date Picker-->
                            <input type="text" style="width:100px"  id="datepicker" class="form-control">
                            <script>
                                $(function() {
                                  $( "#datepicker" ).datepicker();
                                });
                            </script>
                            <button type="button" class="btn btn-danger">بحث</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection
