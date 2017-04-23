<!-- /.navbar-top-links -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav ">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="بحث...">
                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                </div>
                <!-- /input-group -->
            </li>
            <li>
                <a href="{{ URL::to('/') }}"><i class="fa fa-dashboard fa-fw"></i> لوحة التحكم</a>
            </li>
            <li>
                <a href="{{ URL::to('/employee') }}"><i class="fa fa fa-eye fa-fw"></i> صلاحيات الموظف</a>
                <ul>
                    <li><a href="{{ URL::to('/employee') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/employee/create') }}">أضف جديد</a></li>
                    <li><a href="{{ URL::to('/employee/trash') }}">المحذوفات</a></li>
                    <li>
                        <a href="{{ URL::to('/employeeBorrow') }}"><i class="fa fa-money fa-fw"></i> السلفيات </a>
                        <ul>
                            <li><a href="{{ URL::to('/employeeBorrow') }}">الكل</a> </li>
                            <li><a href="{{ URL::to('/employeeBorrow/create') }}">أضف جديد</a> </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/depositwithdraw') }}"><i class="fa fa-briefcase fa-fw"></i>وارد / منصرف</a>
            </li>
            <li>
                <a href="{{ URL::to('/attendance') }}"><i class="fa fa-table fa-fw"></i> حضور/انصراف الموظف</a>
                <ul>
                    <li><a href="{{ URL::to('/attendance') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/attendance/checkin') }}">تسجيل حضور</a></li>
                    <li><a href="{{ URL::to('/attendance/checkout') }}">تسجيل انصراف</a></li>
                    <li><a href="{{ route('attendance.manualadding') }}">تسجيل جديد</a></li>
                    <li><a href="{{ URL::to('/attendance/guardianship/all') }}">سجل العهد</a>
                    <li><a href="{{ URL::to('/attendance/all') }}">مرتبات</a>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/client/process') }}"><i class="fa fa-edit fa-fw"></i> عملية جديدة عميل</a>
                <ul>
                    <li><a href="{{ URL::to('/client/process') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/client/process/create') }}">أضف جديد</a></li>
                    <li><a href="{{ URL::to('/client/process/trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/supplier/process') }}"><i class="fa fa-share-square-o  fa-fw"></i> عملية جديدة مورد</a>
                <ul>
                    <li><a href="{{ URL::to('/supplier/process') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/supplier/process/create') }}">أضف جديد</a></li>
                    <li><a href="{{ URL::to('/supplier/process/trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/client') }}"><i class="fa fa-user fa-fw"></i> عميل جديد</a>
                <ul>
                    <li><a href="{{ URL::to('/client') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/client/create') }}">أضف جديد</a></li>
                    <li><a href="{{ URL::to('/client/trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/supplier') }}"><i class="fa fa-share fa-fw"></i> مورد جديد</a>
                <ul>
                    <li><a href="{{ URL::to('/supplier') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/supplier/create') }}">أضف جديد</a></li>
                    <li><a href="{{ URL::to('/supplier/trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/invoice') }}"><i class="fa fa-share-square-o  fa-fw"></i>إصدار فاتورة</a>
                <ul>
                    <li><a href="{{ URL::to('/invoice') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/invoice/create') }}">أضف جديد</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::to('/expenses') }}"><i class="fa fa-files-o fa-fw"></i> مصروف جديد</a>
                <ul>
                    <li><a href="{{ URL::to('/expenses') }}">الكل</a></li>
                    <li><a href="{{ URL::to('/expenses/create') }}">أضف جديد</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-bank fa-fw"></i> بنك جديد</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-briefcase fa-fw"></i> وارد منصرف بنك</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-list-alt fa-fw"></i> دراسة جدوى العملية</a>
            </li>
            <li style="font-size: 20px;">
                <a href="{{ URL::to('/reports') }}"><i class="fa fa-bar-chart-o"></i>  التقارير</a>
                <ul>
                    <li><a href="{{ URL::to('/reports/client') }}">عميل</a></li>
                    <li><a href="{{ URL::to('/reports/supplier') }}">مورد</a></li>
                    <li><a href="{{ URL::to('/reports/client/ClientAnalyticalCenter') }}">المركز التحليلى للعملاء</a></li>
                    <li><a href="{{ URL::to('/reports/supplier/SupplierAnalyticalCenter') }}">المركز التحليلى للموردين</a></li>
                    <li><a href="{{ URL::to('/reports/expenses') }}">استعلام عن مصروف</a></li>
                    <li><a href="{{ URL::to('/reports/employee') }}">مرتبات الشهر</a></li>
                </ul>

            </li>
        </ul>
    </div>
</div>
