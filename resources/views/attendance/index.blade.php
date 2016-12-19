@extends('layouts.app')

@section('title', 'حضور وانصراف الموظفين')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">حضور وانصراف الموظفين</h1>
    </div>
</div>
<!-- /.row -->
<link href="vendors/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="vendors/flatpickr/dist/flatpickr.min.js"></script>
<script src="vendors/flatpickr/dist/l10n/ar.js"></script>

<div class="row">
    <form role="form">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    بيانات الحضور والانصراف
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <div class="col-lg-6 ">
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox">غياب
                            </label>
                        </div>

                        <div class="form-group">
                            <label>اسم الموظف</label>
                            <input class="form-control" id="tags" placeholder="ادخل اسم الموظف">
                        </div>

                    </div>
                    <div class="col-lg-6 ">
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox">عمليات ادارية
                            </label>
                        </div>
                        <div class="form-group">

                            <label>اسم العملية</label>
                            <input class="form-control" id="operation" placeholder="ادخل اسم العملية">
                        </div>
                    </div>

                    <div class="col-lg-6  clearboth">
                        <div class="form-group">
                            <label>التاريخ</label>
                            <input type="text"  id="datepicker" placeholder="D/M/Y" class="form-control">
                            <script>
$(function () {
    $("#datepicker").flatpickr({
        enableTime: true,
        locale: "ar"
    }); 
});
                            </script>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="form-group">
                            <label>الخصومات</label>
                            <input class="form-control" placeholder="ادخل الخصومات">
                        </div>
                    </div>

                    <div class="col-lg-6  clearboth">
                        <label>الساعة</label>
                        <div class="form-group">

                            <div class="col-lg-12 no-padding" >
                                <input class="form-control" > 
                            </div> 
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="form-group">
                            <label>المكافأت</label>
                            <input class="form-control" placeholder="ادخل المكافأت">
                        </div>
                    </div>

                    <div class="col-lg-6  clearboth">

                        <div class="form-group">
                            <label>ملاحظات</label>
                            <input class="form-control" placeholder="ادخل الملاحظات">
                        </div>
                    </div>
                    <div class="col-lg-6 "> 
                        <div class="form-group">
                            <label> ساعات العمل</label>
                            <input class="form-control" placeholder="ادخل عدد ساعات العمل">
                        </div>
                    </div>
                    <div class="col-lg-12 no-padding"> 
                        <fieldset disabled>
                            <legend class="scheduler-border">غياب</legend>
                            <div class="col-lg-6 "> 
                                <div class="col-lg-6 ">
                                    <div class="form-group">
                                        <label>
                                            نوع الغياب
                                        </label>
                                        <select class="form-control">
                                            <option>اختر</option>
                                            <option>ذكر</option>
                                            <option>انثى</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group">
                                        <label>
                                            المبلغ
                                        </label>
                                        <input class="form-control" placeholder="ادخل المبلغ">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 ">
                                <div class="form-group buttonsdiv">
                                    <p>
                                        <button class="btn btn-primary" type="submit">حفظ</button>
                                        <button class="btn btn-info" type="submit">تعديل</button>
                                    </p>  
                                </div>
                            </div>
                        </fieldset>
                    </div>



                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <div class="col-lg-12">
            <div class="col-lg-6">
                <div class="form-group buttonsdiv">
                    <button class="btn btn-success" type="button">جديد</button>
                    <button class="btn btn-primary" type="button">حفظ</button>
                    <button class="btn btn-info" type="button">تعديل</button>
                    <button class="btn btn-danger" type="button">حذف</button>

                </div>
            </div>
            <div class="col-lg-6 searchbdiv">
                <div class="col-lg-2 ">
                    <button class="btn btn-warning" type="button">بحث</button>
                </div>
                <div class="col-lg-6 no-padding">
                    <input type="text"  id="datepicker2" placeholder="D/M/Y" class="form-control">
                    <script>
                        $(function () {
                            $("#datepicker2").datepicker();
                        });
                    </script>
                </div>
                <div class="col-lg-4 ">
                    <select class="form-control">
                        <option>اختر</option>
                        <option>خيار1</option>
                        <option>خيار2</option>
                    </select>
                </div>
            </div>
        </div>
        <row class="col-lg-12 clearboth">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            بيانات الموظفين
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">

                                <div class="table-responsive">
                                    <table width="2000" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th width="145">اسم الموظف</th>
                                                <th width="145">اسم العملية</th>
                                                <th width="55">الوقت</th>
                                                <th width="45">التاريخ</th>
                                                <th width="45">الخصم</th>
                                                <th width="150">الملاحظات</th>
                                                <th width="145">العملية</th>
                                                <th width="50">المكافأت</th>
                                                <th width="55">نوع الغياب</th>
                                                <th width="40">القيمة</th>
                                                <th width="50">ساعات العمل</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>

                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even" >
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd" >
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even" >
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd" >
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                            <tr class="even">
                                                <td>احمد محمود السيد</td>
                                                <td >تركيب بانر الحياة</td>
                                                <td>12:00 صباحا</td>
                                                <td>12-12-2015</td>
                                                <td>100</td>
                                                <td>لا توجد ملاحظات</td>
                                                <td>العملية</td>
                                                <td>300</td>
                                                <td>اضطرارى</td>
                                                <td>200</td>
                                                <td>8</td>
                                            </tr>
                                        </tbody>
                                    </table>  
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>


        </row>

    </form>
</div>
<!-- /.row -->
@endsection